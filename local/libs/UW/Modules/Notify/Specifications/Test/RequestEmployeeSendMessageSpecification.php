<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 14.04.2017
 * Time: 10:57
 */

namespace UW\Modules\Notify\Specifications\Test;


use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Specifications\SpecificationUnit;

class RequestEmployeeSendMessageSpecification extends SpecificationUnit
{
    protected $name = 'Отправитель сообщения = Сотрудник';

    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        /*
         * Инициатор события - тот, кто отправил сообщение
         * Владелец сущности - тот, кто создал заявление
         *
         * Если идентификатор владельца сущности НЕ совпадает с идентификатором
         * иницатора события, то считаем, что отправитель сообщения является
         * Сотрудник
         */
        return ($entity->getOwnerId() != $event->getInitiatorId());
    }
}