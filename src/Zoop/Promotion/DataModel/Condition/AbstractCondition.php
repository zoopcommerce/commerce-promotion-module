<?php

namespace Zoop\Promotion\DataModel\Condition;

use Zoop\Promotion\DataModel\Condition\Operator\Conditional\ConditionalInterface;
use Zoop\Promotion\DataModel\Condition\Operator\Logical\LogicalInterface;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\EmbeddedDocument
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
abstract class AbstractCondition
{
    /**
     * @ODM\EmbedOne(
     *   discriminatorField      = "type",
     *   discriminatorMap={
     *     "AndOperator"         = "Zoop\Promotion\DataModel\Condition\Operator\Logical\AndOperator",
     *     "OrOperator"          = "Zoop\Promotion\DataModel\Condition\Operator\Logical\OrOperator"
     *   }
     * )
     */
    protected $logicalOperator;

    /**
     * @ODM\EmbedOne(
     *   discriminatorField     = "type",
     *   discriminatorMap={
     *     "Equal"              = "Zoop\Promotion\DataModel\Condition\Operator\Conditional\Equal",
     *     "NotEqual"           = "Zoop\Promotion\DataModel\Condition\Operator\Conditional\NotEqual",
     *     "GreaterThan"        = "Zoop\Promotion\DataModel\Condition\Operator\Conditional\GreaterThan",
     *     "GreaterThanEqual"   = "Zoop\Promotion\DataModel\Condition\Operator\Conditional\GreaterThanEqual",
     *     "LessThan"           = "Zoop\Promotion\DataModel\Condition\Operator\Conditional\LessThan",
     *     "LessThanEqual"      = "Zoop\Promotion\DataModel\Condition\Operator\Conditional\LessThanEqual"
     *   }
     * )
     */
    protected $conditionalOperator;

    /**
     * @ODM\Int
     * @ODM\Index(order="asc")
     */
    protected $order;

    /**
     *
     * @return LogicalInterface
     */
    public function getLogicalOperator()
    {
        return $this->logicalOperator;
    }

    /**
     *
     * @return ConditionalInterface
     */
    public function getConditionalOperator()
    {
        return $this->conditionalOperator;
    }

    /**
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @param LogicalInterface $logicalOperator
     */
    public function setLogicalOperator(LogicalInterface $logicalOperator)
    {
        $this->logicalOperator = $logicalOperator;
    }

    /**
     *
     * @param ConditionalInterface $conditionalOperator
     */
    public function setConditionalOperator(ConditionalInterface $conditionalOperator)
    {
        $this->conditionalOperator = $conditionalOperator;
    }

    /**
     *
     * @param string $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }
}
