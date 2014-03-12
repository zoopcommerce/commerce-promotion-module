<?php

namespace Zoop\Promotion\Condition;

use Zoop\Promotion\DataModel\Condition\ConditionInterface;

class ConditionChain
{

    private $conditions = [];

    /**
     *
     * @param ConditionInterface $condition
     */
    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;
    }

    /**
     *
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     *
     * @param array $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

}
