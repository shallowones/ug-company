<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 20.04.2017
 * Time: 18:13
 */

namespace UW\Modules\Notify;


use UW\Modules\Notify\Providers\ProviderInterface;

/**
 * Содержит операции, которые могут быть запущены при помощи Агентов битрикса
 * Class NotifyAgents
 * @package UW\Modules\Notify
 */
class NotifyAgents
{
    public static function runProvider($agentName)
    {
        $ns = '\UW\Modules\Notify\Providers';
        $providerClass = sprintf('%s\%sProvider', $ns, $agentName);

        if(class_exists($providerClass)){
            /**
             * @var ProviderInterface $providerClass
             */
            $providerClass::run();
        }

        return '\\'.__METHOD__.'("'.$agentName.'");';
    }
}