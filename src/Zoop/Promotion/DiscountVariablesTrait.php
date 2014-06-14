<?php

namespace Zoop\Promotion;

trait DiscountVariablesTrait
{
    protected static $variableDiscountChainClass = '\Zoop\Promotion\Discount\DiscountChain';
    protected static $variableDiscountChainClassAddDiscountFunction = 'addDiscount';
    protected static $variableCartDiscountClass = '\Zoop\Promotion\Discount\CartDiscount';
    protected static $variableProductDiscountClass = '\Zoop\Promotion\Discount\ProductDiscount';
    protected static $variableDiscountClassSetItemFunction = 'setItem';
    protected static $variableDiscountClassSetProductFunction = 'setProduct';
    protected static $variableDiscountClassSetCartDiscountFunction = 'setCartDiscount';
    protected static $variableDiscountClassSetItemDiscountFunction = 'setItemDiscount';
    protected static $variableDiscountClassSetShippingDiscountFunction = 'setShippingDiscount';
    protected static $variableDiscountClassSetIsAppliedFunction = 'setIsApplied';

    /**
     * @return string
     */
    public static function getVariableCartDiscountClass()
    {
        return self::$variableCartDiscountClass;
    }
    
    /**
     * @return string
     */
    public static function getVariableProductDiscountClass()
    {
        return self::$variableProductDiscountClass;
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
    public static function getVariableCartDiscountClassInstantiation($discountVarName = 'discount')
    {
        return '$' . $discountVarName . ' = new ' . self::getVariableCartDiscountClass() . '();';
    }

    /**
     * @return string
     */
    public static function getVariableProductDiscountClassInstantiation($discountVarName = 'discount')
    {
        return '$' . $discountVarName . ' = new ' . self::getVariableProductDiscountClass() . '();';
    }
    
    /**
     * @return string
     */
    public static function getVariableDiscountClassSetItem($discountVarName = 'discount', $itemsVarName = 'item')
    {
        return '$' . $discountVarName . '->' . self::$variableDiscountClassSetItemFunction . '($' . $itemsVarName . ');';
    }
    
    /**
     * @return string
     */
    public static function getVariableDiscountClassSetProduct($discountVarName = 'discount', $itemsVarName = 'product')
    {
        return '$' . $discountVarName . '->' . self::$variableDiscountClassSetProductFunction . '($' . $itemsVarName . ');';
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
