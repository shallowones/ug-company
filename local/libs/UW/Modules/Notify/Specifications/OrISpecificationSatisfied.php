<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 16.02.2017
 * Time: 15:42
 */

namespace UW\Modules\Notify\Specifications;


use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;

/**
 * Спецификация "Или"
 *
 * Выполняет операцию логического сложения всех внутренних спецификаций
 *
 * Class OrSpecification
 * @package UW\Modules\Notify\Specifications
 */
class OrISpecificationSatisfied extends LogicSpecificationSatisfied
{
    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        $satisfied = [];

        foreach ($this->specifications as $specification) {
            $satisfied[] = $specification->isSatisfiedBy($entity, $event);
        }

        return in_array(true, $satisfied);
    }
}