<?php

namespace UW\Modules\Notify\Specifications\Test;


use UW\Facades\RequestFacade;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Specifications\SpecificationUnit;

class StatusEqualCompleteSpecification extends SpecificationUnit
{
    protected $name = 'Статус = Услуга оказана';

    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        return ((new RequestFacade())->getStatusComplete()['ID'] == $entity->getStatus());
    }
}