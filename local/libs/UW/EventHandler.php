<?

namespace UW;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\EventManager;
use UW\Modules\Notify\NotifyFacade;

class EventHandler
{
    private static function handlers()
    {
        return [
            '' => [
                DataHelper::HL_STATEMENTS . DataManager::EVENT_ON_BEFORE_UPDATE => [
                    ['UW\EventListener', 'beforeStatementUpdate']
                ],

                DataHelper::HL_STATEMENTS . DataManager::EVENT_ON_AFTER_ADD => [
                    ['UW\EventListener', 'afterStatementAdd']
                ]
            ]
        ];
    }

    private static function registerHandlers(array $arHandlers)
    {
        $eventManager = EventManager::getInstance();

        foreach($arHandlers as $module => $arEvents)
        {
            foreach($arEvents as $event => $arHandler)
            {
                foreach($arHandler as $key => $handler)
                {
                    $eventManager->addEventHandler($module, $event, $handler);
                }
            }
        }
    }

    /**
     * Метод регистрирует подписки на события Битрикса и модуля Уведомлений
     */
    public static function register()
    {
        //self::registerHandlers(self::handlers());
        //self::registerHandlers((new NotifyFacade())->getEventHandlers());
    }
}
