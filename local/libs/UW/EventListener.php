<?

namespace UW;

use Bitrix\Main\Entity\Event;

class EventListener
{
    public static function beforeStatementUpdate(Event $event)
    {
        // айдишник элемента
        $itemId = $event->getParameter('id')['ID'];
        // поля, которые подверглись изменению
        $newFields = $event->getParameter('fields');
        // хайлоад
        $hl = $event->getEntity()->getDataClass();
        $params = [
            'select' => ['UF_MESSAGE'],
            'filter' => [
                '=ID' => $itemId
            ]
        ];
        $oldFields = $hl::getRow($params);

        if (array_key_exists('UF_MESSAGE', $newFields)) {
            ServiceController::statementMessageUpdate($itemId, $oldFields['UF_MESSAGE'], $newFields['UF_MESSAGE']);
        }
    }

    public static function afterStatementAdd(Event $event)
    {
        ServiceController::statementAdd($event->getParameter('id')['ID']);
    }
}