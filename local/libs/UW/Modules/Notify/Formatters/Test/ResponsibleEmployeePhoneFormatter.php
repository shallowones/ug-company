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
 * Реализует алгоритм подстановки Телефона ответственного сотрудника
 *
 * Class ResponsibleEmployeePhoneFormatter
 * @package UW\Modules\Notify\Formatters\Requests
 */
class ResponsibleEmployeePhoneFormatter implements IStrategy, IStrategyInforming
{

    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    protected $variableInfo = [
        '#responsibleEmployeePhone' => 'Телефон ответственного сотрудника'
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
        $replacer = new Replacer('#responsibleEmployeePhone', $userData['WORK_PHONE']);

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