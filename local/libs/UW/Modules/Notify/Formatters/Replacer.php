<?php

namespace UW\Modules\Notify\Formatters;

/**
 * Вспомогательный класс для осуществления оперции замены переменных в шаблоне
 *
 * Class Replacer
 * @package UW\Modules\Notify\Formatters
 */
class Replacer
{
    /**
     * Имя переменной для замены
     * @var string
     */
    private $variableName;

    /**
     * Значение, которым необходимо заменить переменную
     * @var string
     */
    private $variableVale;

    /**
     * Выбрать значения для замены
     *
     * В переданное строке метод ищет и обрабатывает конструкцию вида (@variable|default value).
     * Если значение параметра $useDefaultValue = true, то вся конструкция заменяется на блок
     * по умолчанию - "default value". Если $useDefaultValue = false, то вся конструкция заменяется
     * на блок со значением - "@variable"
     *
     * @param $string
     * @param bool $useDefaultValue
     * @return mixed
     */
    private function selectValues($string, $useDefaultValue = false)
    {
        $replacements = ($useDefaultValue) ? '$3' : '$2';

        return preg_replace('/(\(([^\)]*'.$this->variableName.'[^\)]*)\|([^\)]*)\))/', $replacements, $string);
    }

    /**
     * Replacer constructor.
     * @param string $variable Переменная для замены
     * @param string $value Значение
     */
    function __construct($variable, $value)
    {
        $this->variableName = $variable;
        $this->variableVale = $value;
    }

    /**
     * Заменить все переменные в строке
     *
     * @param string $string
     * @return string
     */
    public function run($string)
    {
        $string = $this->selectValues($string, (!$this->variableVale));
        $string = str_replace("{$this->variableName}", $this->variableVale, $string);

        return $string;
    }


}