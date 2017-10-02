<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 04.04.2017
 * Time: 11:53
 */

namespace UW\Modules\Notify\Specifications;


abstract class LogicSpecificationSatisfied implements ISpecificationSatisfied
{
    /**
     * @var ISpecificationSatisfied[]
     */
    protected $specifications;

    /**
     * @param ISpecificationSatisfied[] $specifications
     */
    public function __construct(array $specifications)
    {
        foreach ($specifications as $specification){
            if(!($specification instanceof ISpecificationSatisfied)){
                //todo log
                continue;
            }
        }

        $this->specifications = $specifications;
    }
}