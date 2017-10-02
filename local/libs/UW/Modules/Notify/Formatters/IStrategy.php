<?php

namespace UW\Modules\Notify\Formatters;

use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;

/**
 * Интерфейс предоставляющий стратегию (алгоритм) для форматирования различных данных
 * Interface StrategyInterface
 * @package UW\Modules\Notify\Formatters
 */
interface IStrategy
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
    public function algorithm(array $dataFormatting, IEntity $entity, Event $event);
}