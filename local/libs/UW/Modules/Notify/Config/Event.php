<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 27.03.2017
 * Time: 11:40
 */

namespace UW\Modules\Notify\Config;


use UW\Facades\MainFacade;
use HL\Base as HLHelper;
use UW\Modules\Notify\Exceptions\ConfigException;
use UW\Modules\Notify\Exceptions\ResourceException;

class Event
{
    /**
     * Получить список кодов событий, зарегистрированных для сущности
     *
     * @param $entityCode
     * @return array
     */
    private function getRegisteredEvents($entityCode)
    {
        $hlEE = HLHelper::initByCode(ConfigController::HL_SETTINGS_EE);

        $params = [
            'select' => [
                'UF_EVENT_CODE',
                'ID'
            ],
            'filter' => [
                'UF_ENTITY_TYPE' => $entityCode
            ]
        ];

        $rsEntityEvents = $hlEE::getList($params);
        $arEntityEventCodes = [];

        while ($tmpArr = $rsEntityEvents->fetch()){
            $arEntityEventCodes[] = [
                'id' => $tmpArr['ID'],
                'code' => $tmpArr['UF_EVENT_CODE']
            ];
        }

        return $arEntityEventCodes;
    }

    /**
     * Добавить обработку события
     *
     * @param $eventCode
     * @param $entityCode
     * @return int
     * @throws ConfigException
     * @throws ResourceException
     */
    public function add($eventCode, $entityCode)
    {
        //проверим, можно ли прослушивать событие
        //для этого попробуем найти его в списке доступных для
        //прослушки событий
        $entityEvents = $this->getList($entityCode);
        $eventForSave = current(array_filter(
            $entityEvents,
            function($value) use ($eventCode){
                return $value['code'] == $eventCode && empty($value['id']);
        }));

        if(!$eventForSave){
            throw new ConfigException(
                'Нельзя зарегистрировать указанное событиые', ConfigException::EVENT_UNACCEPTABLE_REGISTER
            );
        }


        $hlEE = HLHelper::initByCode(ConfigController::HL_SETTINGS_EE);

        $fields = [
            'UF_EVENT_CODE' => $eventCode,
            'UF_ENTITY_TYPE' => $entityCode
        ];

        $result = $hlEE::add($fields);

        if(!$result->isSuccess()){
            throw new ResourceException(
                sprintf('Не удалось добавить событие: [%s]', explode(', ', $result->getErrorMessages())),
                ResourceException::DB_CANT_ADD_VALUE
            );
        }

        return $result->getId();
    }

    /**
     * Удалить прослушивание события события
     * @param $eeId
     * @throws ResourceException
     */
    public function delete($eeId)
    {
        $hlEE = HLHelper::initByCode(ConfigController::HL_SETTINGS_EE);
        $result = $hlEE::delete($eeId);

        if(!$result->isSuccess()){
            throw new ResourceException(
                sprintf('Не удалось удалить событие: [%s]', explode(', ', $result->getErrorMessages())),
                ResourceException::DB_CANT_DELETE_VALUE
            );
        }
    }

    /**
     * Получить список событий для сущности
     *
     * @param $entityCode
     * @return array
     */
    public function getList($entityCode)
    {
        $coreEventsList = array_filter(
            (new MainFacade())->getEventsList(),
            function($value) use ($entityCode) {
                return $value['entityType'] == $entityCode;
        });
        $arEntityEvents = $this->getRegisteredEvents($entityCode);

        $result = [];

        foreach ($coreEventsList as $coreEvent){
            foreach ($arEntityEvents as $entityEvent){
                if($coreEvent['code'] == $entityEvent['code']){
                    $result[] = [
                        'id' => intval($entityEvent['id']),
                        'code' => $coreEvent['code'],
                        'name' => $coreEvent['name']
                    ];

                    continue 2;
                }
            }

            $result[] = [
                'code' => $coreEvent['code'],
                'name' => $coreEvent['name']
            ];
        }

        return $result;
    }
}