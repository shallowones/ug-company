<?php

namespace UW\Modules\Notify\Formatters\Common;


use UW\Facades\MainFacade;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Formatters\IStrategy;
use UW\Modules\Notify\Formatters\IStrategyInforming;
use UW\Modules\Notify\Formatters\Replacer;

/**
 * Реализует алгоритм подстановки детального УРЛа страницы проверки статуса заявления/обращения
 *
 * Class ItemCreateFormatter
 * @package UW\Modules\Notify\Formatters\Reception
 */
class CheckingStatusServiceUrlFormatter implements IStrategy, IStrategyInforming
{
    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    private $variableInfo = [
        '#checkingStatusServiceUrl' => 'Адрес страницы сервиса "Проверка статуса обращения/заявления"'
    ];

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
        $mainFacade = new MainFacade();

        $detailUrl = $mainFacade->getCheckingStatusServiceLink();

        $replacer = new Replacer('#checkingStatusServiceUrl', $detailUrl);

        return [
            'title' => $replacer->run($dataFormatting['title']),
            'body'  => $replacer->run($dataFormatting['body'])
        ];
    }

    /**
     * Получить описание переменной, которая обрабатывается в алгоритме
     * @return array
     */
    public function getVariableInfo()
    {
        return $this->variableInfo;
    }
}