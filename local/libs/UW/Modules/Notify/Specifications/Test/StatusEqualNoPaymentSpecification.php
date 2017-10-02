<?php

namespace UW\Modules\Notify\Specifications\Test;


use UW\Facades\RequestFacade;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Specifications\SpecificationUnit;

class StatusEqualNoPaymentSpecification extends SpecificationUnit
{
    protected $name = 'Статус = Нет оплаты';

    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        return ((new RequestFacade())->getStatusNoPayment()['ID'] == $entity->getStatus());
    }
}