<?php

namespace UW\Modules\Notify\Formatters;

/**
 * Интрефейс предоставлет информацию об алгоритме форматирования
 *
 * Interface IStrategyInforming
 * @package UW\Modules\Notify\Formatters
 */
interface IStrategyInforming
{
    /**
     * Получить описание переменной, которая обрабатывается в алгоритме
     * @return array
     */
    public function getVariableInfo();
}