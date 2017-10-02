<?php

namespace UW\Modules\Notify\Specifications;


use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;

/**
 * Неопределенная спецификация
 *
 * Class NullSpecification
 * @package UW\Modules\Notify\Specifications\Request
 */
class NullSpecification extends SpecificationUnit
{
    protected $name = 'Null';

    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        return true;
    }

}