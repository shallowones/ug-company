<?php

namespace UW\Modules\Notify\Formatters;

use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;

/**
 * Форматтер-заглушка.
 *
 * Используется в случае когда не найден ни один подходящий форматтер
 * для обработки переменной
 *
 * Class NullFormatter
 * @package UW\Modules\Notify\Formatters
 */
class NullFormatter implements IStrategy
{

    /**
     * Алгоритм форматирования данных
     *
     * @param array $dataFormatting Данные для форматирования
     * @param IEntity $entity
     * @param Event $event
     * @return array
     * @internal param array $additionalInformation Дополнительные данные
     */
    public function algorithm(array $dataFormatting, IEntity $entity, Event $event)
    {
        return $dataFormatting;
    }
}