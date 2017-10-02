<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 22.02.2017
 * Time: 10:11
 */

namespace UW\Modules\Notify\Formatters;

use UW\Logger;

/**
 * Фабрика для создания объектов для форматирования данных
 *
 * Class FormatterFactory
 * @package UW\Modules\Notify\Formatters
 */
class FormatterFactory
{

    /**
     * Создать коллекцию форматтеров
     * @param $folder
     * @param $nameSpace
     * @return array
     */
    private function createFormatters($folder, $nameSpace)
    {
        $result = [];

        foreach (scandir($folder) as $file) {
            $fileInfo = pathinfo($folder . '/' . $file);


            if ('php' == $fileInfo['extension']) {
                $class = $nameSpace . '\\' . $fileInfo['filename'];

                if (!class_exists($class)) {
                    //todo log

                    continue;
                }

                $reflection = new \ReflectionClass($class);

                if ($reflection->isInstantiable() &&
                    $reflection->implementsInterface('\UW\Modules\Notify\Formatters\IStrategyInforming') &&
                    $reflection->implementsInterface('\UW\Modules\Notify\Formatters\IStrategy')
                ) {

                    $result[] = new $class();
                }
            }
        }

        return $result;
    }

    /**
     * Создать главный форматтер
     *
     * @return Formatter
     */
    public function create()
    {
        return new Formatter();
    }

    /**
     * Создаеть форматтер на основе символьного кода переменной, которую он обрабатывает
     *
     * @param $variableCode
     * @param $entityCode
     * @return IStrategy
     */
    public function createFormatterByVariable($variableCode, $entityCode = null)
    {
        //если код сущности не задан, то пытаемся создать форматтер для обработки получателей
        if(null == $entityCode){
            $class = '\UW\Modules\Notify\Formatters\Consumers\\' . ucfirst($variableCode) . 'Formatter';
        } else {
            $class = '\UW\Modules\Notify\Formatters\\' . $entityCode . '\\' . ucfirst($variableCode) . 'Formatter';
        }


        if (!class_exists($class)) {
            Logger::trace(sprintf('Не найден класс форматтера - %s', $class));
            Logger::warn(sprintf('Не найден класс форматтера - %s', $class));

            return new NullFormatter();
        }

        return new $class;
    }

    /**
     * Создать коллекцию форматтеров для сущности
     * @param $entityCode
     * @return IStrategyInforming[]
     */
    public function createEntityFormatters($entityCode)
    {
        $ns = 'UW\Modules\Notify\Formatters\\' . $entityCode;
        $folder = __DIR__ . '/' . $entityCode;

        return $this->createFormatters($folder, $ns);
    }

    /**
     * Создать коллекцию форматтеров для получателей
     * @return IStrategyInforming[]
     */
    public function createConsumersFormatters()
    {
        $ns = 'UW\Modules\Notify\Formatters\Consumers';
        $folder = __DIR__ . '/Consumers';

        return $this->createFormatters($folder, $ns);
    }
}