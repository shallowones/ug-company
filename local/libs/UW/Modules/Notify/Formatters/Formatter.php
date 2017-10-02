<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 22.02.2017
 * Time: 16:47
 */

namespace UW\Modules\Notify\Formatters;

use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;


/**
 * Отвечает за форматирование данных различного типа
 *
 * Class Formatter
 * @package UW\Modules\Notify\Formatters
 */
class Formatter
{
    /**
     * @var array
     */
    protected $dataFormatting;
    /**
     * @var IEntity
     */
    protected $entity;
    /**
     * @var Event
     */
    protected $event;
    /**
     * @var IStrategy[]
     */
    protected $formatters;

    /**
     * Formatter constructor.
     */
    function __construct()
    {
        $this->additionalInformation = [];
        $this->dataFormatting= [];
    }

    /**
     * Установить данные, которые необходимо отформатировать
     * @param array $dataFormatting
     * @return $this
     */
    public function setDataFormatting($dataFormatting)
    {
        $this->dataFormatting = $dataFormatting;

        return $this;
    }

    /**
     * Установить сущность
     * @param IEntity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Установить событие
     * @param Event $event
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Установить набор форматтеров для преобразования данных
     *
     * @param IStrategy[] $formatters
     * @return $this
     */
    public function setFormatters(array $formatters)
    {
        $this->formatters = $formatters;

        return $this;
    }

    /**
     * Выполнить преобразование
     * @return array
     */
    public function execute()
    {
        foreach ($this->formatters as $formatter){
            $this->dataFormatting = $formatter->algorithm($this->dataFormatting, $this->entity, $this->event);
        }
        return $this->dataFormatting;
    }

}