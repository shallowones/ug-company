<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 15.02.2017
 * Time: 16:06
 */

namespace UW\Modules\Notify;


use Bitrix\Main\Type\DateTime;
use HL\Base as HLBase;
use UW\Facades\MainFacade;
use UW\Logger;
use UW\Modules\Notify\Exceptions\ResourceException;

class EventStorage
{
    const HL_CODE = 'NoticeEventsLog';

    /**
     * Восстановить объект из БД
     *
     * @param $eventFields
     *
     * @return Event
     *
     * @throws \InvalidArgumentException
     */
    protected function buildEvent($eventFields)
    {
        if($eventFields['UF_DATE_TIME'] instanceof DateTime){
            $dateTime = (new \DateTime())->setTimestamp($eventFields['UF_DATE_TIME']->getTimestamp());
        } else {
            throw new \InvalidArgumentException();
        }

        return new Event(
            $eventFields['UF_ENTITY_ID'],
            $eventFields['UF_ENTITY_CODE'],
            json_decode($eventFields['UF_DATA'], true),
            $eventFields['UF_EVENT_CODE'],
            $eventFields['UF_INITIATOR_ID'],
            $dateTime,
            $eventFields['EVENT_ID']
        );
    }

    /**
     * Сохранить произошедшее событие
     *
     * @param Event $event
     * @return int
     * @throws ResourceException
     */
    public function save(Event $event)
    {
        Logger::trace('Попытка сохранения объекта Событие');

        $hlClass = HLBase::initByCode(self::HL_CODE);

        $arNotifyFields = [
            'UF_INITIATOR_ID' => $event->getInitiatorId(),
            'UF_EVENT_CODE' => $event->getCode(),
            'UF_ENTITY_CODE' => $event->getEntityCode(),
            'UF_DATA' => json_encode($event->getData()),
            'UF_DATE_TIME' => $event->getDateTime()->format('d.m.Y H:i:s'),
            'UF_ENTITY_ID' => $event->getEntityId()
        ];

        $result = $hlClass::add($arNotifyFields);
        if($result->isSuccess()){
            Logger::trace('Объект Событие успено сохранен');
            Logger::trace('Регистрация события завершена');
            Logger::endTrace();

            return $result->getId();
        }

        throw new ResourceException('Не удается сохранить событие', ResourceException::DB_CANT_ADD_VALUE);
    }

    /**
     * Возвращаяет список прошедших событий с конкретной даты
     *
     * @param string $eventCode Код события
     * @param \DateTime $date Дата отсчета
     * @return Event[]
     */
    public function getOccurredEvents($eventCode, \DateTime $date)
    {
        $hlClass = HLBase::initByCode(self::HL_CODE);

        $coreEvents = (new MainFacade())->getEventsList();

        $arParams = [
            'filter' => [
                'UF_EVENT_CODE' => $eventCode,
                '>UF_DATE_TIME' => $date->format('d.m.Y H:i:s')
            ]
        ];

        $list = $hlClass::getList($arParams)->fetchAll();
        $result = [];
        if($list){
            foreach ($list as $arEvent){

                $sourceEvent = current(array_filter(
                    $coreEvents,
                    function($value) use ($arEvent) {
                        return $value['code'] == $arEvent['UF_EVENT_CODE'];
                    }
                ));

                if(!$sourceEvent){
                    Logger::trace('Не найдено событие ядра для создание объекта Событие');
                    Logger::warn(sprintf(
                        'Не найдено событие ядра для создание объекта Событие (код: %s, сущность: %s)',
                        $arEvent['UF_EVENT_CODE'],
                        $arEvent['UF_ENTITY_CODE']
                    ));

                    continue;
                }

                $arEvent['EVENT_ID'] = $sourceEvent['id'];

                try {
                    $result[] = $this->buildEvent($arEvent);
                } catch (\Exception $e) {
                    Logger::trace(sprintf(
                        'Не удалось создать объект Событие (code: %s, $id: %d)',
                        $eventCode,
                        $arEvent['EVENT_ID']
                    ));

                    Logger::fatal('Не удалось создать объект Событие', $e);
                }
            }
        }

        return $result;
    }


}