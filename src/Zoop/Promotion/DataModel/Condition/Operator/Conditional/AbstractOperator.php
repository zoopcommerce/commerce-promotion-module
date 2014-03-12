<?php

namespace Zoop\Promotion\DataModel\Condition\Operator\Conditional;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

abstract class AbstractOperator
{

    /**
     * @ODM\String
     */
    protected $operator;

    public function getOperator()
    {
        return $this->operator;
    }

    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    public function __toString()
    {
        return $this->getOperator();
    }

}
