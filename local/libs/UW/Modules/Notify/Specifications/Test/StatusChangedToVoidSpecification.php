<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 16.02.2017
 * Time: 14:01
 */

namespace UW\Modules\Notify\Specifications\Test;


use UW\Facades\RequestFacade;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Specifications\SpecificationUnit;

class StatusChangedToVoidSpecification extends SpecificationUnit
{
    protected $name = 'Статус изменен на "Аннулировано"';

    public function isSatisfiedBy(IEntity $entity, Event $event)
    {
        return ((new RequestFacade())->getStatusVoid()['ID'] == $event->getData()['newStatus']);
    }

}