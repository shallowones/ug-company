<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.02.2017
 * Time: 16:00
 */

namespace UW\Modules\Notify;


use Bitrix\Main\Event as BXEvent;
use UW\Logger;
use UW\Modules\Notify\Config\ConfigController;

/**
 * Отвечает за подписку модуля на события
 * Class Handler
 * @package UW\Modules\Notify
 */
class Handler
{
    /**
     * Метод возвращает список прослушиваемых событий
     *
     * @return array
     */
    public static function getHandlers()
    {
        $configController = new ConfigController();
        $moduleId = '';
        $arHandlers = [];

        foreach ($configController->getEventsList() as $eventCode){
            $arHandlers[$eventCode] = [
                ['UW\Modules\Notify\Handler', 'handleEvents']
            ];
        }

        return [$moduleId => $arHandlers];
    }

    /**
     * Обрабатывает события, на которые подписан модуль Уведомлений
     *
     * @param BXEvent $event
     */
    public static function handleEvents(BXEvent $event)
    {
        //трассировка регистрации события
        Logger::startTrace();
        Logger::trace(
            sprintf(
                'Начало регистрации события [%s]. Параметры: %s',
                $event->getEventType(),
                json_encode($event->getParameters())
            )
        );

        (new Controller())->registerEvent($event->getEventType(), $event->getParameters());
    }
}