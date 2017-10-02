<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 16.02.2017
 * Time: 14:04
 */

namespace UW\Modules\Notify\Specifications;


use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;

interface ISpecificationSatisfied
{
    /**
     * Проверить, удовлетворяет ли сущность спецификации
     * @param IEntity $entity
     * @param Event $event
     * @return bool
     */
    public function isSatisfiedBy(IEntity $entity, Event $event);
}