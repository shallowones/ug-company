<?php

namespace UW\Modules\Notify\Entities;

/**
 * Базовый класс для работы с сущностями Заявление, Интернет-приемная и т.д.
 *
 * @package UW\Modules\Notify\Entities
 */
abstract class Entity
{
    /**
     * Идентификатор сущности
     * @var int
     */
    protected $id;
    /**
     * Статус
     * @var string
     */
    protected $status;
    /**
     * Создатель/владелец
     * @var int
     */
    protected $owner;
    /**
     * Номер сущности
     * @var string
     */
    protected $number;
    /**
     * Дата подачи
     * @var \DateTime
     */
    protected $fillingDate;

    /**
     * Entity constructor.
     * @param int $id
     * @param string $status
     * @param string $entityNumber
     * @param int $owner
     * @param null $fillingDate
     */
    public function __construct($id, $status, $entityNumber, $owner = 0, $fillingDate = null)
    {
        $this->id = $id;
        $this->owner = intval($owner);
        $this->status = $status;
        $this->number = $entityNumber;
        $this->fillingDate = (null == $fillingDate) ? new \DateTime() : $fillingDate;
    }

    /**
     * Получить номер сущности
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Получить владельца
     * @return int
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Получить статус
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int Получить идентификатор сущности
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Получить дату создания сущности
     *
     * Для обращений и заявления устанавливается "Дата подачи сущности"
     *
     * @return \DateTime
     */
    public function getFillingDate()
    {
        return $this->fillingDate;
    }
}