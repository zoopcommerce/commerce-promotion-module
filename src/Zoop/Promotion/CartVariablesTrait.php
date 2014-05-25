<?php

namespace Zoop\Promotion;

trait CartVariablesTrait
{
    protected static $variableOrderPrefix = '$order';
    protected static $variableCartTotalQuantity = '->getTotal()->getProductQuantity()';
    protected static $variableCartTotalPrice = '->getTotal()->getOrderPrice()';
    protected static $variableCartTotalWholesalePrice = '->getTotal()->getProductWholesalePrice()';
    protected static $variableCartTotalProductPrice = '->getTotal()->getProductListPrice()';
    protected static $variableCartTotalDiscountPrice = '->getTotal()->getDiscountPrice()';
    protected static $variableCartTotalShippingPrice = '->getTotal()->getShippingPrice()';
    protected static $variableCartShippingType = '->getShippingMethod()';
    protected static $variableCartShippingCountry = '->getAddress()->getCountry()';
    protected static $variableCartProducts = '->getItems()';
    protected static $variableCartProductPrefix = '$product';
    protected static $variableCartProductId = '->getLegacyId()';
    protected static $variableCartProductFullPrice = '->getPrice()->getList()';
    protected static $variableCartProductWholesalePrice = '->getPrice()->getWholesale()';
    protected static $variableCartProductQuantity = '->getQuantity()';
    protected static $variableCartProductShippingPrice = '->getPrice()->getShipping()';
    protected static $variableConditionalProducts = '$conditionalProducts';

    public static function getVariableOrder()
    {
        return self::$variableOrderPrefix;
    }

    public static function getVariableOrderTotalQuantity()
    {
        return self::$variableOrderPrefix . self::$variableCartTotalQuantity;
    }

    public static function getVariableOrderTotalPrice()
    {
        return self::$variableOrderPrefix . self::$variableCartTotalPrice;
    }

    public static function getVariableOrderTotalWholesalePrice()
    {
        return self::$variableOrderPrefix . self::$variableCartTotalWholesalePrice;
    }

    public static function getVariableOrderTotalProductPrice()
    {
        return self::$variableOrderPrefix . self::$variableCartTotalProductPrice;
    }

    public static function getVariableOrderTotalDiscountPrice()
    {
        return self::$variableOrderPrefix . self::$variableCartTotalDiscountPrice;
    }

    public static function getVariableOrderTotalShippingPrice()
    {
        return self::$variableOrderPrefix . self::$variableCartTotalShippingPrice;
    }

    public static function getVariableOrderShippingType()
    {
        return self::$variableOrderPrefix . self::$variableCartShippingType;
    }

    public static function getVariableOrderShippingCountry()
    {
        return self::$variableOrderPrefix . self::$variableCartShippingCountry;
    }

    public static function getVariableOrderProducts()
    {
        return self::$variableOrderPrefix . self::$variableCartProducts;
    }

    public static function getVariableOrderProductPrefix()
    {
        return self::$variableCartProductPrefix;
    }

    public static function getVariableOrderProductId()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductId;
    }

    public static function getVariableOrderProductFullPrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductFullPrice;
    }

    public static function getVariableOrderProductWholesalePrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductWholesalePrice;
    }

    public static function getVariableOrderProductQuantity()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductQuantity;
    }

    public static function getVariableOrderProductShippingPrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductShippingPrice;
    }

    public static function getVariableConditionalProducts()
    {
        return self::$variableConditionalProducts;
    }

    public function getCartFunctionArguments()
    {
        $arguments = [
            $this->getVariableOrder(),
        ];

        return implode(', ', $arguments);
    }

    public function getCartDiscountRuleFunctionArguments()
    {
        $arguments = [
            $this->getVariableOrder(),
        ];

        return implode(', ', $arguments);
    }
}
