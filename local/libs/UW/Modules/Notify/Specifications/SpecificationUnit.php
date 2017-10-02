<?php

namespace UW\Modules\Notify\Specifications;



/**
 * Базовый класс для спецификации
 *
 * Несет декоративную функцию - снимает со всех наследников обязанность по
 * реализации методов получения справочной информации об спецификации.
 *
 * Class SpecificationUnit
 * @package UW\Modules\Notify\Specifications
 */
abstract class SpecificationUnit implements ISpecificationSatisfied, ISpecificationInforming
{
    /**
     * Имя спецификации
     * @var string
     */
    protected $name;

    /**
     * Получить имя спецификации
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}