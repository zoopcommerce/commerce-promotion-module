<?php

namespace Zoop\Promotion\DataModel\Discount;

use Zoop\Promotion\DataModel\Condition\ConditionInterface;
use Zoop\Promotion\DataModel\Discount\Variable\VariableInterface;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\EmbeddedDocument
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
abstract class AbstractDiscount
{

    /**
     * @ODM\String
     */
    protected $value;

    /**
     * @ODM\EmbedOne(
     *   discriminatorField="type",
     *   discriminatorMap={
     *     "Order"          = "Zoop\Promotion\DataModel\Discount\Variable\Order",
     *     "Product"       = "Zoop\Promotion\DataModel\Discount\Variable\Product",
     *     "Shipping"       = "Zoop\Promotion\DataModel\Discount\Variable\Shipping"
     *   }
     * )
     */
    protected $variable;

    /**
     * @ODM\EmbedMany(
     *  discriminatorField="type",
     *  discriminatorMap={
     *     "OrderPrice"         = "Zoop\Promotion\DataModel\Condition\OrderPrice",
     *     "OrderQuantity"      = "Zoop\Promotion\DataModel\Condition\OrderQuantity",
     *     "ProductFullPrice"   = "Zoop\Promotion\DataModel\Condition\ProductFullPrice",
     *     "ProductQuantity"    = "Zoop\Promotion\DataModel\Condition\ProductQuantity",
     *     "ShippingCountry"    = "Zoop\Promotion\DataModel\Condition\ShippingCountry",
     *     "ShippingPrice"      = "Zoop\Promotion\DataModel\Condition\ShippingPrice",
     *     "ShippingType"       = "Zoop\Promotion\DataModel\Condition\ShippingType"
     *   }
     * )
     */
    protected $conditions;

    /**
     * @ODM\String
     */
    protected $level;

    /**
     * @ODM\String
     */
    protected $compiledFunction;

    /**
     * @ODM\String
     */
    protected $compiledCondition;

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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     * @param array $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

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
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     *
     * @return VariableInterface
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     *
     * @param VariableInterface $variable
     */
    public function setVariable(VariableInterface $variable)
    {
        $this->variable = $variable;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     *
     * @return string
     */
    public function getCompiledFunction()
    {
        return $this->compiledFunction;
    }

    /**
     *
     * @return string
     */
    public function getCompiledCondition()
    {
        return $this->compiledCondition;
    }

    /**
     *
     * @param string $compiledFunction
     */
    public function setCompiledFunction($compiledFunction)
    {
        $this->compiledFunction = $compiledFunction;
    }

    /**
     *
     * @param string $compiledCondition
     */
    public function setCompiledCondition($compiledCondition)
    {
        $this->compiledCondition = $compiledCondition;
    }

}
