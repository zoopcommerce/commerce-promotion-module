<?php

namespace Zoop\Promotion;

trait CartVariablesTrait
{
    protected static $variableCartPrefix = '$cart';
    protected static $variableCartTotalQuantity = 'TotalQuantity';
    protected static $variableCartTotalPrice = 'TotalPrice';
    protected static $variableCartTotalWholesalePrice = 'TotalWholesalePrice';
    protected static $variableCartTotalProductPrice = 'TotalProductPrice';
    protected static $variableCartTotalDiscountPrice = 'TotalDiscountPrice';
    protected static $variableCartTotalShippingPrice = 'TotalShippingPrice';
    protected static $variableCartShippingType = 'ShippingType';
    protected static $variableCartShippingCountry = 'ShippingCountry';
    protected static $variableCartProducts = 'Products';
    protected static $variableCartProductPrefix = '$product';
    protected static $variableCartProductId = 'productId';
    protected static $variableCartProductFullPrice = 'fullPrice';
    protected static $variableCartProductWholesalePrice = 'wholesalePrice';
    protected static $variableCartProductQuantity = 'quantity';
    protected static $variableCartProductShippingPrice = 'totalShipping';
    protected static $variableConditionalProducts = '$conditionalProducts';

    public static function getVariableCartPrefix()
    {
        return self::$variableCartPrefix;
    }

    public static function getVariableCartTotalQuantity()
    {
        return self::$variableCartPrefix . self::$variableCartTotalQuantity;
    }

    public static function getVariableCartTotalPrice()
    {
        return self::$variableCartPrefix . self::$variableCartTotalPrice;
    }

    public static function getVariableCartTotalWholesalePrice()
    {
        return self::$variableCartPrefix . self::$variableCartTotalWholesalePrice;
    }

    public static function getVariableCartTotalProductPrice()
    {
        return self::$variableCartPrefix . self::$variableCartTotalProductPrice;
    }

    public static function getVariableCartTotalDiscountPrice()
    {
        return self::$variableCartPrefix . self::$variableCartTotalDiscountPrice;
    }

    public static function getVariableCartTotalShippingPrice()
    {
        return self::$variableCartPrefix . self::$variableCartTotalShippingPrice;
    }

    public static function getVariableCartShippingType()
    {
        return self::$variableCartPrefix . self::$variableCartShippingType;
    }

    public static function getVariableCartShippingCountry()
    {
        return self::$variableCartPrefix . self::$variableCartShippingCountry;
    }

    public static function getVariableCartProducts()
    {
        return self::$variableCartPrefix . self::$variableCartProducts;
    }

    public static function getVariableCartProductPrefix()
    {
        return self::$variableCartProductPrefix;
    }

    public static function getVariableCartProductId()
    {
        return self::$variableCartProductPrefix . "['" . self::$variableCartProductId . "']";
    }

    public static function getVariableCartProductFullPrice()
    {
        return self::$variableCartProductPrefix . "['" . self::$variableCartProductFullPrice . "']";
    }

    public static function getVariableCartProductWholesalePrice()
    {
        return self::$variableCartProductPrefix . "['" . self::$variableCartProductWholesalePrice . "']";
    }

    public static function getVariableCartProductQuantity()
    {
        return self::$variableCartProductPrefix . "['" . self::$variableCartProductQuantity . "']";
    }

    public static function getVariableCartProductShippingPrice()
    {
        return self::$variableCartProductPrefix . "['" . self::$variableCartProductShippingPrice . "']";
    }

    public static function getVariableConditionalProducts()
    {
        return self::$variableConditionalProducts;
    }

    public function getCartFunctionArguments()
    {
        $arguments = [
            $this->getVariableCartTotalQuantity(),
            $this->getVariableCartTotalPrice(),
            $this->getVariableCartTotalWholesalePrice(),
            $this->getVariableCartTotalProductPrice(),
            $this->getVariableCartTotalDiscountPrice(),
            $this->getVariableCartTotalShippingPrice(),
            $this->getVariableCartShippingType(),
            $this->getVariableCartShippingCountry(),
            $this->getVariableCartProducts()
        ];

        return implode(', ', $arguments);
    }

    public function getCartDiscountRuleFunctionArguments()
    {
        $arguments = [
            $this->getVariableCartTotalQuantity(),
            $this->getVariableCartTotalPrice(),
            $this->getVariableCartTotalWholesalePrice(),
            $this->getVariableCartTotalProductPrice(),
            $this->getVariableCartTotalDiscountPrice(),
            $this->getVariableCartTotalShippingPrice(),
            $this->getVariableCartShippingType(),
            $this->getVariableCartShippingCountry(),
            $this->getVariableCartProducts(),
            $this->getVariableCartProductPrefix(),
            $this->getVariableConditionalProducts()
        ];

        return implode(', ', $arguments);
    }
}
