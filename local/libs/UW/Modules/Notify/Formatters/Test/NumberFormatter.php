<?php

namespace UW\Modules\Notify\Formatters\Test;

/**
 * Реализует алгоритм подстановки строкового номера заявления
 *
 * Class NumberFormatter
 * @package UW\Modules\Notify\Formatters\Requests
 */
class NumberFormatter extends \UW\Modules\Notify\Formatters\Common\NumberFormatter
{
    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    protected $variableInfo = [
        '#number' => 'Строковый номер заявления'
    ];
}