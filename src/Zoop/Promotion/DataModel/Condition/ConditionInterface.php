<?php

namespace Zoop\Promotion\DataModel\Condition;

use Zoop\Promotion\DataModel\Condition\Operator\Conditional\ConditionalInterface;
use Zoop\Promotion\DataModel\Condition\Operator\Logical\LogicalInterface;

interface ConditionInterface
{

    public function getLogicalOperator();

    public function getConditionalOperator();

    public function getValue();

    public function getOrder();

    public function setLogicalOperator(LogicalInterface $logicalOperator);

    public function setConditionalOperator(ConditionalInterface $conditionalOperator);

    public function setValue($value);

    public function setOrder($order);
}
