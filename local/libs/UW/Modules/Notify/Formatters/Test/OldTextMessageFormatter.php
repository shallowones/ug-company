<?php

namespace UW\Modules\Notify\Formatters\Test;

use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Formatters\IStrategyInforming;
use UW\Modules\Notify\Formatters\Replacer;
use UW\Modules\Notify\Formatters\IStrategy;

/**
 * Реализует алгоритм подстановки строкового значения "старого" текста сообщения заявления
 *
 * Class OldTextMessageFormatter
 * @package UW\Modules\Notify\Formatters\Requests
 */
class OldTextMessageFormatter implements IStrategy, IStrategyInforming
{
    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    private $variableInfo = [
        '#oldTextMessage' => 'Старый текст сообщения'
    ];

    /**
     * Получить описание переменной, которая обрабатывается в алгоритме
     * @return array
     */
    public function getVariableInfo()
    {
        return $this->variableInfo;
    }

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
        $replacer = new Replacer('#oldTextMessage', $event->getData()['oldMessageText']);

        return [
            'title' => $replacer->run($dataFormatting['title']),
            'body'  => $replacer->run($dataFormatting['body'])
        ];
    }
}