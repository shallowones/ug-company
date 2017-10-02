<?php

namespace UW\Modules\Notify\Providers;


use Bitrix\Main\Application;
use Bitrix\Main\Entity\ExpressionField;
use HL\Base as HLHelper;
use UW\Logger;
use UW\Modules\Notify\Controller;
use UW\Modules\Notify\MetaMessage;

/**
 * Провайдер для логирования действий пользователей
 *
 * Class ActionProvider
 * @package UW\Modules\Notify\Providers
 */
class ActivityProvider extends Provider
{
    /**
     * Код HL-блкоа
     */
    const HL_CODE = 'NoticeProviderActivity';

    protected $name = 'Лог действий пользователей';
    protected $code = 'Activity';

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
        $provider = new ActivityProvider();

        //трассировка получения сообщений для провайдера
        Logger::startTrace();
        Logger::trace('Начало работы провайдра Activity');

        $provider
            ->setTimeProcessMessaging(new \DateTime())
            ->setMessages(
                (new Controller())->getMetaMessages(
                    $provider->getCode(),
                    $provider->getLastQueryDate()
                )
            )
            ->save();

        Logger::trace('Окончание работы провайдра Activity');
        Logger::endTrace();

        return '\\' . __METHOD__ . '();';
    }

    /**
     * Получить список элементов
     *
     * @param array $params Параметры для фильтрации
     * @return array
     */
    public function getItems($params)
    {
        $hlClass = HLHelper::initByCode(self::HL_CODE);

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
        $hlClass = HLHelper::initByCode(self::HL_CODE);

        if(empty($params['select'])){
            $params['select'] = [new ExpressionField('CNT', 'COUNT(*)')];
        } else {
            $params['select'] = array_merge($params['select'], [new ExpressionField('CNT', 'COUNT(*)')]);
        }

        $arParams = $this->helper->prepareParams($params);

        return $hlClass::getList($arParams)->fetch()['CNT'];
    }

    /**
     * Сохранить уведомления
     */
    public function save()
    {
        $hlClass = HLHelper::initByCode(self::HL_CODE);

        Application::getConnection()->startTransaction();

        foreach ($this->messages as $message) {

            $arNotifyFields = [
                'UF_ITEM_ID'   => $message->getEvent()->getEntityId(),
                'UF_ITEM_TYPE' => $message->getEvent()->getEntityCode(),
                'UF_EVENT_ID'  => $message->getEvent()->getId(),
                'UF_BODY'      => $message->getTemplate()->getBody(),
                'UF_TITLE'     => $message->getTemplate()->getTitle(),
                'UF_DATE'      => $message->getEvent()->getDateTime()->format('d.m.Y H:i:s'),
                'UF_USER_ID'   => $message->getEvent()->getInitiatorId()
            ];

            $result = $hlClass::add($arNotifyFields);

            if (!$result->isSuccess()) {
                Application::getConnection()->rollbackTransaction();

                Logger::trace('Не удалось сохранить одно из сообщений');
                Logger::warn(sprintf(
                    'Не удалось сохранить сообщение для провайдера Activity. Структура сообщения - %s',
                    json_encode($arNotifyFields)
                ));

                return;
            }
        }


        $this->setLastQueryDate($this->timeProcessMessaging);
        Application::getConnection()->commitTransaction();

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

}