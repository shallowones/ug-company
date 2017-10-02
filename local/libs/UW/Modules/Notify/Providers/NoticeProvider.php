<?php

namespace UW\Modules\Notify\Providers;


use Bitrix\Main\Application;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\ReferenceField;
use HL\Base as HLHelper;
use UW\Logger;
use UW\Modules\Notify\Controller;
use UW\Modules\Notify\MetaMessage;

class NoticeProvider extends Provider
{
    /**
     * Код HL-блкоа с уведомлениями
     */
    const HL_CODE_NOTICE = 'NoticeProviderNotifications';
    const HL_CODE_NOTICE_CONSUMERS = 'NoticeProviderNotificationsConsumers';

    /**
     * Группа получателей - клиенты
     */
    const CONSUMER_GROUP_CLIENT = 'client';
    /**
     * Группа получателей - персонал
     */
    const CONSUMER_GROUP_STAFF = 'staff';

    /**
     * Код провайдера
     *
     * @var string
     */
    protected $code = 'Notice';
    protected $name = 'Лента уведомлений';

    /**
     * Список сообщений для отправки
     *
     * @var MetaMessage[]
     */
    protected $messages;


    /**
     * Запустить сбор и отправку уведомлений
     *
     * Используется для работы Битрикс-агента
     */
    public static function run()
    {
        $provider = new NoticeProvider();

        //трассировка получения сообщений для провайдера
        Logger::startTrace();
        Logger::trace('Начало работы провайдра Notice');

        $provider
            ->setTimeProcessMessaging(new \DateTime())
            ->setMessages((new Controller())->getMetaMessages($provider->getCode(), $provider->getLastQueryDate()))
            ->saveNotifications();

        Logger::trace('Окончание работы провайдра Notice');
        Logger::endTrace();

        return '\\' . __METHOD__ . '();';
    }

    /**
     * Записать получатлей уведомления
     *
     * @param int $itemId Идентификатор уведомления
     * @param array $consumers Получатели
     * @return bool
     */
    protected function saveConsumers($itemId, array $consumers)
    {
        $class = HLHelper::initByCode(self::HL_CODE_NOTICE_CONSUMERS);

        foreach ($consumers as $consumerId){

            $group = self::CONSUMER_GROUP_STAFF;
            $arFields = [
                'UF_NOTIFICATION_ID' => $itemId,
            ];

            if(intval($consumerId) > 0){
                $group = self::CONSUMER_GROUP_CLIENT;
            } elseif(intval($consumerId) < 0) {
                /*
                 * Получатель не был найден, в таком случае нет
                 * необходимости сохранять уведомление
                 */

                return false;
            }

            $arFields['UF_CONSUMERS_GROUP'] = $group;

            if(self::CONSUMER_GROUP_CLIENT == $group){
                $arFields['UF_CONSUMER_ID'] = $consumerId;
            }

            $result = $class::add($arFields);

            if(!$result->isSuccess()){

                Logger::trace('Не удалось сохранить одного из получателей сообщения');
                Logger::warn(sprintf(
                    'Не удалось сохранить получателя сообщения для провайдера Notify. Структура - %s',
                    json_encode($arFields)
                ));

                return false;
            }
        }

        return true;
    }

    /**
     * Включает в выборку информаицю о получателях
     *
     * @param array $params Параметры выборки
     * @param array $defaultSelect Набор полей по-умолчанию
     * @return array
     */
    protected function includeConsumersDataIntoParams($params, array $defaultSelect)
    {
        $hlConsumers = HLHelper::initByCode(self::HL_CODE_NOTICE_CONSUMERS);

        $select = [
            'CONSUMER_ID' => 'Consumers.UF_CONSUMER_ID',
            'CONSUMER_GROUP' => 'Consumers.UF_CONSUMERS_GROUP'
        ];

        if(empty($params['select'])){
            $params['select'] = array_merge($defaultSelect, $select);
        } else {
            $params['select'] = array_merge($params['select'], $select);
        }

        $params['runtime'] = [
            new ReferenceField(
                'Consumers',
                $hlConsumers,
                ['this.ID' => 'ref.UF_NOTIFICATION_ID']
            )
        ];

        return $params;
    }


    /**
     * Получить список элементов
     *
     * @param array $params Параметры для фильтрации
     * @return array
     */
    public function getItems($params)
    {
        $hlClass = HLHelper::initByCode(self::HL_CODE_NOTICE);

        $params = $this->includeConsumersDataIntoParams($params, ['*']);
        $arParams = $this->helper->prepareParams($params);

        return $hlClass::getList($arParams)->fetchAll();
    }


    /**
     * Получить число эелментов
     *
     * @param array $params Параметры для фильтрации
     * @return int
     */
    public function getItemsCount($params)
    {
        $hlClass = HLHelper::initByCode(self::HL_CODE_NOTICE);

        $params = $this->includeConsumersDataIntoParams($params, [new ExpressionField('CNT', 'COUNT(*)')]);
        $arParams = $this->helper->prepareParams($params);

        return $hlClass::getList($arParams)->fetch()['CNT'];
    }

    /**
     * Сохранить уведомления
     */
    public function saveNotifications()
    {
        $classNotify = \HLWrap::init(self::HL_CODE_NOTICE);
        $conn = Application::getConnection();


        foreach ($this->messages as $message) {

            $conn->startTransaction();

            $arNotifyFields = [
                'UF_ELEMENT_ID'   => $message->getEvent()->getEntityId(),
                'UF_ELEMENT_TYPE' => $message->getEvent()->getEntityCode(),
                'UF_EVENT_ID'     => $message->getEvent()->getId(),
                'UF_BODY'         => $message->getTemplate()->getBody(),
                'UF_TITLE'        => $message->getTemplate()->getTitle(),
                'UF_DATE'         => $message->getEvent()->getDateTime()->format('d.m.Y H:i:s')
            ];

            $result = $classNotify::add($arNotifyFields);

            if ($result->isSuccess()) {
                if(!$this->saveConsumers($result->getId(), $message->getConsumers())){
                    $conn->rollbackTransaction();

                    continue;
                }

                $conn->commitTransaction();
            } else {

                Logger::trace('Не удалось сохранить одно из сообщений');
                Logger::warn(sprintf(
                    'Не удалось сохранить сообщение для провайдера Notify. Структура сообщения - %s',
                    json_encode($arNotifyFields)
                ));

                continue;
            }
        }


        $this->setLastQueryDate($this->timeProcessMessaging);
    }

    /**
     * Установить сообщения для отправки
     * @param MetaMessage[] $messages
     *
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Установить для уведомления флаг "Прочитано/Не прочитано"
     *
     * @param $itemId
     * @param $isRead
     */
    public function setIsRead($itemId, $isRead)
    {
        $hlClass = HLHelper::initByCode(self::HL_CODE_NOTICE);

        $result = $hlClass::update($itemId, ['UF_READ' => ($isRead) ? 'Y' : 'N']);

        if($result->isSuccess()){

        } else {
            //todo error
        }

    }
}