<?php

namespace UW\Modules\Notify\Specifications\Test;


use UW\Facades\RequestFacade;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Specifications\SpecificationUnit;

class StatusEqualPendingSpecification extends SpecificationUnit
{
    protected $name = 'Статус = На рассмотрении';

    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        return ((new RequestFacade())->getStatusPending()['ID'] == $entity->getStatus());
    }
}