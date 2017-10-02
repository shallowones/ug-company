<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 14.04.2017
 * Time: 15:50
 */

namespace UW\Modules\Notify\Entities;

/**
 * Расширяет каждую сущность - предоставляет доступ к общим методам
 * Class EntityHelper
 * @package UW\Modules\Notify\Entities
 */
trait EntityHelper
{
    protected $id;
    protected $status;
    protected $owner;
    protected $number;

    public function __construct($id, $status, $owner, $number = '', $props = [])
    {
        $this->id = $id;
        $this->owner = intval($owner);
        $this->status = $status;
        $this->number = (!$number) ? $id : $number;

        foreach ($props as $field => $value){
            if (property_exists($this, $field)) {
                $this->$field = $value;
            }
        }
    }


    public function getOwnerId()
    {
        return $this->owner;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}