<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 20.02.2017
 * Time: 15:15
 */

namespace UW\Modules\Notify;


use UW\Logger;
use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Specifications\AndISpecificationSatisfied;
use UW\Modules\Notify\Specifications\ISpecificationSatisfied;
use UW\Modules\Notify\Specifications\NullSpecification;
use UW\Modules\Notify\Specifications\OrISpecificationSatisfied;

class Rule
{
    /**
     * @var ISpecificationSatisfied
     */
    protected $specification;
    /**
     * @var array
     */
    protected $consumers;

    /**
     * Построить правило
     *
     * @param array $specifications
     * @param $logic
     * @param array $consumers
     * @return Rule
     */
    public static function build(array $specifications, $logic, array $consumers)
    {
        //если спецификации отсутствуют, то устанавливаем пустую спецификацию,
        //после проверки которой мы гарантировано получим список получателей
        if(empty($specifications)){
            return new self(new NullSpecification(), $consumers);
        }

        switch ($logic) {
            case 'or':
                $rule = new self(new OrISpecificationSatisfied($specifications), $consumers);
                break;

            case 'and':
                $rule = new self(new AndISpecificationSatisfied($specifications), $consumers);
                break;

            default:
                Logger::warn(sprintf(
                    'При построении правила используется не допустимая логическая операция - %s',
                    $logic
                ));

                $rule = new self(new AndISpecificationSatisfied($specifications), $consumers);
                break;
        }

        return $rule;
    }

    function __construct(ISpecificationSatisfied $specification, $consumers)
    {
        $this->specification = $specification;
        $this->consumers = $consumers;
    }

    /**
     * Получить список адресатов
     *
     * @return array
     */
    public function getConsumers()
    {
        return $this->consumers;
    }

    /**
     * Проверка выполнения правила для сущности
     *
     * @param IEntity $entity
     *
     * @param Event $event
     * @return mixed
     */
    public function complyWith(IEntity $entity, Event $event)
    {
        return $this->specification->isSatisfiedBy($entity, $event);
    }
}