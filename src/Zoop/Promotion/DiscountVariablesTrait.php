<?php

namespace Zoop\Promotion;

trait DiscountVariablesTrait
{
    protected static $variableDiscountChainClass = '\Zoop\Promotion\Discount\DiscountChain';
    protected static $variableDiscountChainClassAddDiscountFunction = 'addDiscount';
    protected static $variableDiscountClass = '\Zoop\Promotion\Discount\Discount';
    protected static $variableDiscountClassSetItemsFunction = 'setItems';
    protected static $variableDiscountClassAddItemFunction = 'addItem';
    protected static $variableDiscountClassSetCartDiscountFunction = 'setCartDiscount';
    protected static $variableDiscountClassSetItemDiscountFunction = 'setItemDiscount';
    protected static $variableDiscountClassSetShippingDiscountFunction = 'setShippingDiscount';
    protected static $variableDiscountClassSetIsAppliedFunction = 'setIsApplied';

    /**
     * @return string
     */
    public static function getVariableDiscountClass()
    {
        return self::$variableDiscountClass;
    }

    /**
     * @return string
     */
    public static function getVariableDiscountChainClass()
    {
        return self::$variableDiscountChainClass;
    }

    /**
     * @return string
     */
    public static function getVariableDiscountChainClassInstantiation($discountChainVarName = 'discountChain')
    {
        return '$' . $discountChainVarName . ' = new ' . self::getVariableDiscountChainClass() . '();';
    }

    /**
     * @return string
     */
    public static function getVariableDiscountChainClassAddDiscount($discountChainVarName = 'discountChain', $discountVarName = 'discount')
    {
        return '$' . $discountChainVarName . '->' . self::$variableDiscountChainClassAddDiscountFunction . '($' . $discountVarName . ');';
    }

    /**
     * @return string
     */
    public static function getVariableDiscountClassInstantiation($discountVarName = 'discount')
    {
        return '$' . $discountVarName . ' = new ' . self::getVariableDiscountClass() . '();';
    }
    /**
     * @return string
     */
    public static function getVariableDiscountClassSetItems($discountVarName = 'discount', $itemsVarName = 'items')
    {
        return '$' . $discountVarName . '->' . self::$variableDiscountClassSetItemsFunction . '($' . $itemsVarName . ');';
    }

    /**
     * @return string
     */
    public static function getVariableDiscountClassAddItem($discountVarName = 'discount', $itemVarName = 'item')
    {
        return '$' . $discountVarName . '->' . self::$variableDiscountClassAddItemFunction . '($' . $itemVarName . ');';
    }

    /**
     * @return string
     */
    public static function getVariableDiscountClassSetShippingDiscount($discountVarName = 'discount', $discountAmountVarName = 'discountAmount')
    {
        return '$' . $discountVarName . '->' . self::$variableDiscountClassSetShippingDiscountFunction . '($' . $discountAmountVarName . ');';
    }

    /**
     * @return string
     */
    public static function getVariableDiscountClassSetCartDiscount($discountVarName = 'discount', $discountAmountVarName = 'discountAmount')
    {
        return '$' . $discountVarName . '->' . self::$variableDiscountClassSetCartDiscountFunction . '($' . $discountAmountVarName . ');';
    }

    /**
     * @return string
     */
    public static function getVariableDiscountClassSetItemDiscount($discountVarName = 'discount', $discountAmountVarName = 'discountAmount')
    {
        return '$' . $discountVarName . '->' . self::$variableDiscountClassSetItemDiscountFunction . '($' . $discountAmountVarName . ');';
    }

    /**
     * @return string
     */
    public static function getVariableDiscountClassSetIsApplied($discountVarName = 'discount', $isApplied = true)
    {
        return '$' . $discountVarName . '->' . self::$variableDiscountClassSetIsAppliedFunction . '(' . ("$isApplied") . ');';
    }
}
