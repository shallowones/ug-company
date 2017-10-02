<?

namespace UW;

use Bitrix\Main\Event;

class ServiceController
{
    private static function createEventData($itemId, $entity, array $data = [])
    {
        return array_merge($data, [
            '__itemId' => $itemId,
            '__entity' => $entity
        ]);
    }

    /**
     * Метод регистрирует события "заявлений"
     *
     * @param string $itemId
     * @param string $eventCode
     * @param array $data
     */
    private static function registerStatementEvent($itemId, $eventCode, array $data = [])
    {
        $eventData = self::createEventData($itemId, DataHelper::HL_STATEMENTS, $data);

        (new Event('', $eventCode, $eventData))->send();
    }

    /**
     * Метод регистрирует событие обновления поля "сообщение" в заявлении
     *
     * @param string $itemId
     * @param string $oldMessage
     * @param string $newMessage
     */
    public static function statementMessageUpdate($itemId, $oldMessage, $newMessage)
    {
        self::registerStatementEvent($itemId, EventHelper::CHANGE_MESSAGE, [
            'oldMessage' => $oldMessage,
            'newMessage' => $newMessage
        ]);
    }

    /**
     * Метод регистрирует событие добавления заявления
     *
     * @param string $itemId
     */
    public static function statementAdd($itemId)
    {
        self::registerStatementEvent($itemId, EventHelper::ADD);
    }
}