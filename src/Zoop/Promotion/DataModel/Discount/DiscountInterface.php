<?php

namespace Zoop\Promotion\DataModel\Discount;

use Zoop\Promotion\DataModel\Condition\ConditionInterface;
use Zoop\Promotion\DataModel\Discount\Variable\VariableInterface;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

interface DiscountInterface
{
    public function getConditions();

    public function getValue();

    public function setConditions($conditions);

    public function addCondition(ConditionInterface $condition);

    public function setValue($value);

    public function getVariable();

    public function setVariable(VariableInterface $variable);

    public function getLevel();

    public function setLevel($level);

    public function getCompiledFunction();

    public function getCompiledCondition();

    public function setCompiledFunction($compiledFunction);

    public function setCompiledCondition($compiledCondition);
}
