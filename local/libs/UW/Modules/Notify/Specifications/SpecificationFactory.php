<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 16.02.2017
 * Time: 15:58
 */

namespace UW\Modules\Notify\Specifications;

use UW\Logger;

/**
 * Статичная фабрика для создания спецификаций
 *
 * Class SpecificationFactory
 * @package UW\Modules\Notify\Specifications
 */
class SpecificationFactory
{
    /**
     * Создать коллекцию спецификаций
     *
     * @param $folder
     * @param $nameSpace
     * @return array
     */
    private function createSpecifications($folder, $nameSpace)
    {
        $result = [];

        foreach (scandir($folder) as $file) {
            $fileInfo = pathinfo($folder . '/' . $file);


            if ('php' == $fileInfo['extension']) {
                $class = $nameSpace . '\\' . $fileInfo['filename'];

                if (!class_exists($class)) {
                    Logger::warn(sprintf('Не найден класс спецификации - %s', $class));

                    continue;
                }

                $reflection = new \ReflectionClass($class);

                if ($reflection->isInstantiable()) {
                    $result[] = new $class();
                }
            }
        }

        return $result;
    }

    /**
     * Построить спецификацию
     *
     * @param string $specificationCode Код спецификации
     * @param string $entityCode Код сущности
     * @return ISpecificationSatisfied
     */
    public static function create($specificationCode, $entityCode)
    {
        $nameSpace = '\UW\Modules\Notify\Specifications\\' . $entityCode .'\\';
        $class = $nameSpace . $specificationCode;

        if(class_exists($class)){
            return new $class();
        } else {
            Logger::trace(sprintf('Не найден класс спецификации - %s', $class));
            Logger::warn(sprintf('Не найден класс спецификации - %s', $class));
            return new NullSpecification();
        }
    }


    /**
     * Получить коллекцию спецификаций для сущности
     *
     * @param $entityCode
     * @return array
     */
    public function createEntitySpecificationsList($entityCode)
    {
        $ns = 'UW\Modules\Notify\Specifications\\' . $entityCode;
        $folder = __DIR__ . '/' . $entityCode;

        return $this->createSpecifications($folder, $ns);
    }
}