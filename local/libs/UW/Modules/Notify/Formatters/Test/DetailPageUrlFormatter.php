<?php

namespace UW\Modules\Notify\Formatters\Test;


use UW\Facades\MainFacade;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Formatters\IStrategy;
use UW\Modules\Notify\Formatters\IStrategyInforming;
use UW\Modules\Notify\Formatters\Replacer;

/**
 * Реализует алгоритм подстановки детального УРЛа заявления
 *
 * Class DetailPageUrlFormatter
 * @package UW\Modules\Notify\Formatters\Requests
 */
class DetailPageUrlFormatter implements IStrategy, IStrategyInforming
{
    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    private $variableInfo = [
        '#detailPageUrl' => 'Адрес детальной страницы заявления'
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

        $detailUrl = $mainFacade->formatPrivateAreaDetailPageURL(
            $mainFacade->getPrivateAreaFolder() . $mainFacade->getPrivateAreaLinks()['request']['detail'],
            $entity->getId()
        );

        $replacer = new Replacer('#detailPageUrl', $detailUrl);

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