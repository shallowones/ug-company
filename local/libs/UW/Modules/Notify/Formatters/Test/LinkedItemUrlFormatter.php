<?php

namespace UW\Modules\Notify\Formatters\Test;

use UW\Facades\MainFacade;
use UW\Facades\RequestFacade;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Formatters\IStrategy;
use UW\Modules\Notify\Formatters\IStrategyInforming;
use UW\Modules\Notify\Formatters\Replacer;

/**
 * Реализует алгоритм подстановки детального УРЛа связанного заявления
 *
 * Class LinkedItemUrlFormatter
 * @package UW\Modules\Notify\Formatters\Requests
 */
class LinkedItemUrlFormatter implements IStrategy, IStrategyInforming
{
    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    private $variableInfo = [
        '#linkedItemUrl' => 'Адрес детальной страницы связанного заявления'
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
        $requestFacade = new RequestFacade();

        $entity = $requestFacade->getItem($event->getData()['relationItemId']);

        $detailUrl = $mainFacade->formatPrivateAreaDetailPageURL(
            $mainFacade->getPrivateAreaFolder() . $mainFacade->getPrivateAreaLinks()['request']['detail'],
            $entity['id']
        );

        $replacer = new Replacer('#linkedItemUrl', $detailUrl);

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