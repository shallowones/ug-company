<?php

namespace UW\Modules\Notify;


use UW\Logger;
use UW\Modules\Notify\Config\ConfigController;
use UW\Modules\Notify\Entities\EntityStorage;
use UW\Modules\Notify\Exceptions\EntityException;
use UW\Modules\Notify\Exceptions\NotifyException;

/**
 * Контроллер модуля Уведомления.
 *
 * Предоставляет доступ к сообщениям различным провайдерам на основании конфигурации
 *
 * Class Controller
 * @package UW\Modules\Notify
 */
class Controller
{
    /**
     * @var EntityStorage
     */
    private $entityStorage;

    /**
     * @var ConfigController
     */
    private $config;

    /**
     * @var EventStorage
     */
    private $eventStorage;

    /**
     * Controller constructor.
     */
    function __construct()
    {
        $this->entityStorage = new EntityStorage();
        $this->eventStorage = new EventStorage();
        $this->config = new ConfigController();
    }

    /**
     * Зарегистрирвоать информацию о произошедшем событии
     *
     * @param $eventType
     * @param $eventParameters
     */
    public function registerEvent($eventType, $eventParameters)
    {
        try{
            (new EventStorage())->save(Event::build($eventType, $eventParameters));
        } catch (NotifyException $e) {
            Logger::trace(sprintf('Регистрация события завершилась с ошибкой: %s', $e->getMessage()));
            Logger::endTrace();
            Logger::fatal('Ошибка регистрации события', $e);
        }
    }

    /**
     * Получить данные (события, шаблоны, получателей) для провайдера
     *
     * @param string $providerCode Код провайдера
     * @param \DateTime $providerLastQueryDate Дата и время последнего обращения провайдера
     * @return array
     */
    protected function getProviderData($providerCode, \DateTime $providerLastQueryDate)
    {
        $result = [];
        Logger::trace('Формирование списка конфигураций даных провайдера');

        /** @var ProviderConfigData $providerConfigData */
        foreach ($this->config->getProviderDataCollection($providerCode) as $providerConfigData){

            Logger::trace(
                sprintf(
                    'Провайдер [%s] (тип события [%s], тип сущности [%s])',
                    $providerCode,
                    $providerConfigData->getEventCode(),
                    $providerConfigData->getEntityCode()
                )
            );

            Logger::trace('Формирование списка прошедших событий');
            $occurredEvents = $this->eventStorage->getOccurredEvents(
                $providerConfigData->getEventCode(), $providerLastQueryDate
            );

            //для каждого события определим сущность и выполним необоходимые преобразования
            foreach ($occurredEvents as $event){

                //использование копии объекта необходимо, что бы сохранить изначальное состояние провайдера
                //для тех случаев, когда подряд обрабатывается два и более одинаковых события
                $providerConfigDataItem = clone $providerConfigData;

                Logger::trace(sprintf('Событие ID: %d [%s]', $event->getId(), $providerConfigDataItem->getEventCode()));

                try {
                    $entity = $this->entityStorage->get($event->getEntityCode(), $event->getEntityId());
                } catch (EntityException $e) {
                    Logger::trace(sprintf(
                        'Не удалось создать сущность (id: %d, code: %s)',
                        $event->getEntityId(),
                        $event->getEntityCode()
                    ));

                    Logger::fatal('В процессе формирования конфигурации провайдера не удалось создать сущность', $e);

                    continue;
                }

                Logger::trace(sprintf('Сущность ID: %d [%s]', $entity->getId(), $providerConfigDataItem->getEntityCode()));

                try {
                    $providerConfigDataItem
                        ->setTriggeredEvent($event)
                        ->setTriggeredEntity($entity)
                        ->applyRules()
                        ->applyConsumersAlgorithm()
                        ->applyTemplateAlgorithm();
                } catch (\Exception $e) {
                    Logger::trace('Не удалось произвести обработку конфигурации данных провайдера');
                    Logger::fatal('В процессе форматирования конфигурации данных провайдера произошла ошибка', $e);

                    continue;
                }

                $result[] = [
                    'template' => $providerConfigDataItem->getTemplate(),
                    'consumers' => $providerConfigDataItem->getConsumers(),
                    'event' => $event
                ];

                unset($providerConfigDataItem);
            }
        }

        Logger::trace('Формирование списка конфигураций даных провайдера завершено');

        return $result;
    }

    /**
     * Получить список доступных для провайдера сообщений
     * (Используется провайдерами, которые отправляют точечные уведомления - email, sms и т.д.)
     *
     * @param string $providerCode
     * @param \DateTime $providerLastQueryDate
     *
     * @return Message[]
     */
    public function getMessages($providerCode, \DateTime $providerLastQueryDate)
    {
        $arMessages = [];
        Logger::trace('Формирование списка сообщений');

        foreach ($this->getProviderData($providerCode, $providerLastQueryDate) as $data){
            $arMessages[] = new Message($data['template'], $data['consumers']);
        }

        Logger::trace('Список сообщений успешно сформирован');

        return $arMessages;
    }

    /**
     * Получить список доступных для провайдера сообщений
     * (Используется провайдерами, которые отправляют отправляют групповые (не персонализированные)
     *  уведомления - notify, activity)
     *
     * @param string $providerCode
     * @param \DateTime $providerLastQueryDate
     *
     * @return MetaMessage[]
     */
    public function getMetaMessages($providerCode, \DateTime $providerLastQueryDate)
    {
        $arMessages = [];
        Logger::trace('Формирование списка мета-сообщений');

        foreach ($this->getProviderData($providerCode, $providerLastQueryDate) as $data){
            /**
             * @var Event $event
             */
            $event = $data['event'];
            $arMessages[] = new MetaMessage(
                $data['template'],
                $event,
                $data['consumers']
            );
        }

        Logger::trace('Список мета-сообщений успешно сформирован');

        return $arMessages;
    }
}