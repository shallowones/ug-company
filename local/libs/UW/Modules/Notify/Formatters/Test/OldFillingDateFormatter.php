<?php

namespace UW\Modules\Notify\Formatters\Test;

use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Formatters\IStrategyInforming;
use UW\Modules\Notify\Formatters\Replacer;
use UW\Modules\Notify\Formatters\IStrategy;

/**
 * Реализует алгоритм подстановки строкового значения "старой" даты подачи заявления
 *
 * Class OldFillingDateFormatter
 * @package UW\Modules\Notify\Formatters\Requests
 */
class OldFillingDateFormatter implements IStrategy, IStrategyInforming
{
    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    private $variableInfo = [
        '#oldFillingDate' => 'Старая дата подачи заявления'
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
        $date = (new \DateTime())->setTimestamp(strtotime($event->getData()['oldDate']));
        $oldDate = $date->format('d.m.Y');
        $replacer = new Replacer('#oldFillingDate', $oldDate);

        return [
            'title' => $replacer->run($dataFormatting['title']),
            'body'  => $replacer->run($dataFormatting['body'])
        ];
    }
}