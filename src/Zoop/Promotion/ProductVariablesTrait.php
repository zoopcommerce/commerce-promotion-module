<?php

namespace Zoop\Promotion;

trait ProductVariablesTrait
{
    protected static $variableProductPrefix = '$product';
    protected static $variableProductId = '->getLegacyId()';
    protected static $variableProductFullPrice = '->getPrice()->getFull()';
    protected static $variableProductSalePrice = '->getPrice()->getSale()';
    protected static $variableProductWholesalePrice = '->getPrice()->getWholesale()';

    public static function getVariableProduct()
    {
        return self::$variableProductPrefix;
    }

    public static function getVariableProductId()
    {
        return self::getVariableProduct() . self::$variableProductId;
    }

    public static function getVariableProductWholesalePrice()
    {
        return self::getVariableProduct() . self::$variableProductWholesalePrice;
    }

    public static function getVariableProductFullPrice()
    {
        return self::getVariableProduct() . self::$variableProductFullPrice;
    }

    public function getProductFunctionArguments()
    {
        $arguments = [
            $this->getVariableProduct()
        ];

        return implode(', ', $arguments);
    }

    public function getProductDiscountRuleFunctionArguments()
    {
        $arguments = [
            $this->getVariableProduct()
        ];

        return implode(', ', $arguments);
    }
}
