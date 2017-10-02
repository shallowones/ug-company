<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 14.04.2017
 * Time: 15:42
 */

namespace UW\Modules\Notify\Entities;

/**
 * Представляет базовые операции которыми должна обладать сущность
 *
 * Interface IEntity
 * @package UW\Modules\Notify\Entities
 */
interface IEntity
{
    /**
     * Получить идентификатор сущности
     * @return int
     */
    public function getId();

    /**
     * Получить идентификатор владельца сущности
     * @return int
     */
    public function getOwnerId();

    /**
     * Получить статус сущности
     * @return mixed
     */
    public function getStatus();

    /**
     * Получить номер
     * @return mixed
     */
    public function getNumber();
}