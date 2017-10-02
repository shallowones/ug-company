<?php

namespace UW\Modules\Notify\Specifications\Test;


use UW\Facades\RequestFacade;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Specifications\SpecificationUnit;

class StatusChangedToSuspendedSpecification extends SpecificationUnit
{
    protected $name = 'Статус изменен на "Подача приостановлена"';

    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        return ((new RequestFacade())->getStatusSuspended()['ID'] == $event->getData()['newStatus']);
    }

}