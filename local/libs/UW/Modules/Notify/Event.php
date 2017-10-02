<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 15.02.2017
 * Time: 16:08
 */

namespace UW\Modules\Notify;

use UW\Facades\MainFacade;
use UW\Logger;
use UW\Modules\Notify\Entities\Statements;
use UW\Modules\Notify\Exceptions\EventException;

/**
 * Описывает зарегистрированное модулем событие
 *
 * Class Event
 * @package UW\Modules\Notify
 */
class Event
{
    /**
     * Идентификатор события (EE)
     * @var int
     */
    protected $id;
    /**
     * Пользователь, инициировавший событие
     * @var int
     */
    protected $initiatorId;
    /**
     * Дата и время события
     * @var \DateTime
     */
    protected $dateTime;
    /**
     * Код события
     * @var string
     */
    protected $code;
    /**
     * Данные по событию
     * @var array
     */
    protected $data;
    /**
     * Код сущности, в которой произошло событие
     * @var string
     */
    protected $entityCode;
    /**
     * Идентификатор сущности, в которой произошло событие
     * @var int
     */
    protected $entityId;

    /**
     * Построить объект
     *
     * @param $type
     * @param array $data
     * @return Event
     * @throws EventException
     */
    public static function build($type, array $data)
    {
        Logger::trace('Попытка создания объекта Событие');

        $userId = (new \CUser)->GetID();
        $userId = (null != $userId) ? $userId : 0;
        $entityId = intval($data['__itemId']);
        $entityCode = trim($data['__entity']);

        unset($data['__itemId']);
        unset($data['__entity']);

        if ($entityId == 0) {
            throw new EventException(
                'В информации о событии отсутствует идентификатор сущности',
                EventException::ENTITY_ID_NOT_FOUND
            );
        }

        if ('' == $entityCode || null == $entityCode) {
            throw new EventException(
                'В информации о событии отсутствует символьный код сущности',
                EventException::ENTITY_CODE_NOT_FOUND
            );
        }

        $event = new self($entityId, $entityCode, $data, $type, $userId, (new \DateTime()));
        Logger::trace('Объект успешно создан');

        return $event;
    }


    /**
     * Event constructor.
     * @param int $entityId Идентификатор сущности
     * @param string $entityCode Код сущности
     * @param array $data Данные
     * @param string $eventCode Код события
     * @param int $eventId Идентификатор события
     * @param int $initiatorId ID пользователя, инициировавшего событие
     * @param \DateTime $dateTime
     */
    public function __construct(
        $entityId,
        $entityCode,
        array $data,
        $eventCode,
        $initiatorId,
        \DateTime $dateTime,
        $eventId = null
    ) {
        $this->entityId = $entityId;
        $this->entityCode = $entityCode;
        $this->initiatorId = $initiatorId;
        $this->dateTime = $dateTime;
        $this->code = $eventCode;
        $this->data = $data;
        $this->id = $eventId;
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @return mixed
     */
    public function getInitiatorId()
    {
        return $this->initiatorId;
    }


    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getEntityCode()
    {
        return $this->entityCode;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}