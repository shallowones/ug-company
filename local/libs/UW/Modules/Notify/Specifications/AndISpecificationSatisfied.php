<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 16.02.2017
 * Time: 15:44
 */

namespace UW\Modules\Notify\Specifications;


use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;

/**
 * Спецификация "И"
 *
 * Выполняет операцию логического умножения всех внутренних спецификаций
 *
 * Class AndSpecification
 * @package UW\Modules\Notify\Specifications
 */
class AndISpecificationSatisfied extends LogicSpecificationSatisfied
{
    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        $satisfied = [];

        foreach ($this->specifications as $specification) {
            $satisfied[] = $specification->isSatisfiedBy($entity, $event);
        }

        return !in_array(false, $satisfied);
    }
}