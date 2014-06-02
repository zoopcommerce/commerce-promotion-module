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
    protected static $variableCartProductTotalListPrice = '->getPrice()->getTotal()->getList()';
    protected static $variableCartProductTotalProductDiscountPrice = '->getPrice()->getTotal()->getProductDiscount()';
    protected static $variableCartProductTotalCartDiscountPrice = '->getPrice()->getTotal()->getCartDiscount()';
    protected static $variableCartProductTotalShippingDiscountPrice = '->getPrice()->getTotal()->getShippingDiscount()';
    protected static $variableCartProductTotalWholesalePrice = '->getPrice()->getTotal()->getWholesale()';
    protected static $variableCartProductTotalShippingPrice = '->getPrice()->getTotal()->getShipping()';
    protected static $variableCartProductTotalSalePrice = '->getPrice()->getTotal()->getSale()';
    protected static $variableCartProductTotalSubTotalPrice = '->getPrice()->getTotal()->getSubTotal()';
    protected static $variableCartProductQuantity = '->getQuantity()';
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

    public static function getVariableOrderProductTotalListPrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductTotalListPrice;
    }

    public static function getVariableOrderProductTotalProductDiscountPrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductTotalProductDiscountPrice;
    }

    public static function getVariableOrderProductTotalCartDiscountPrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductTotalCartDiscountPrice;
    }

    public static function getVariableOrderProductTotalShippingDiscountPrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductTotalShippingDiscountPrice;
    }
    
    public static function getVariableOrderProductTotalWholesalePrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductTotalWholesalePrice;
    }

    public static function getVariableOrderProductTotalShippingPrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductTotalShippingPrice;
    }

    public static function getVariableOrderProductTotalSalePrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductTotalSalePrice;
    }

    public static function getVariableOrderProductTotalSubTotalPrice()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductTotalSubTotalPrice;
    }

    public static function getVariableOrderProductQuantity()
    {
        return self::$variableCartProductPrefix . self::$variableCartProductQuantity;
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
            $this->getVariableOrderProductPrefix(),
        ];

        return implode(', ', $arguments);
    }
}
