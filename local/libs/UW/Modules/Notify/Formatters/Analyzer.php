<?php
namespace UW\Modules\Notify\Formatters;


/**
 * Содержит инструменты для анализа информации с целью поиска переменных
 * и создания форматтеров для их дальнейшей обработки
 *
 * Class Analyzer
 * @package UW\Modules\Notify\Formatters
 */
class Analyzer
{
    /**
     * @var FormatterFactory
     */
    private $formatterFactory;

    /**
     * Найти все переменные для замены
     * @param $data
     * @return array
     */
    private function searchUniqueVariablesList($data)
    {
        $arVariables = [];

        foreach ($data as $value){
            if(!is_string($value)){
                continue;
            }

            $matches = [];
            if(preg_match_all('/#([a-zA-Z0-9]+)/', $value, $matches)){
                $arVariables = array_merge($arVariables, $matches[1]);
            }
        }

        return array_unique($arVariables);
    }

    /**
     * Analyzer constructor.
     */
    function __construct()
    {
        $this->formatterFactory = new FormatterFactory();
    }

    /**
     * Получить список форматтеров
     *
     * @param $entityCode
     * @param $data
     * @return IStrategy[]
     */
    public function getFormatters($data, $entityCode = null)
    {
        $templateFormatters = [];
        //извлекаем из шаблона коды переменных и на их основании создаем форматтеры
        foreach ($this->searchUniqueVariablesList($data) as $variableCode){
            $templateFormatters[] = $this->formatterFactory->createFormatterByVariable(
                $variableCode, $entityCode
            );
        }

        return $templateFormatters;
    }
}