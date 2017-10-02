<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 13.04.2017
 * Time: 15:01
 */

namespace UW\Modules\Notify\Formatters\Test;


use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Formatters\IStrategy;
use UW\Modules\Notify\Formatters\IStrategyInforming;
use UW\Modules\Notify\Formatters\Replacer;

/**
 * Реализует алгоритм подстановки ФИО ответственного сотрудника
 *
 * Class ResponsibleEmployeeFIOFormatter
 * @package UW\Modules\Notify\Formatters\Requests
 */
class ResponsibleEmployeeFIOFormatter implements IStrategy, IStrategyInforming
{

    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    protected $variableInfo = [
        '#responsibleEmployeeFIO' => 'ФИО ответственного сотрудника'
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
        $userData = (new \CUser)->GetByID($event->getData()['employeId'])->Fetch();

        $replacer = new Replacer(
            '#responsibleEmployeeFIO',
            "{$userData['LAST_NAME']} {$userData['NAME']} {$userData['SECOND_NAME']}"
        );

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