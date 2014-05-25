<?php

namespace Zoop\Promotion;

trait ProductVariablesTrait
{

    protected static $variableProductPrefix = '$product';
    protected static $variableProductId = 'Id';
    protected static $variableProductWholesalePrice = 'WholesalePrice';
    protected static $variableProductFullPrice = 'FullPrice';

    public static function getVariableProductPrefix()
    {
        return self::$variableProductPrefix;
    }

    public static function getVariableProductId()
    {
        return self::$variableProductPrefix . self::$variableProductId;
    }

    public static function getVariableProductWholesalePrice()
    {
        return self::$variableProductPrefix . self::$variableProductWholesalePrice;
    }

    public static function getVariableProductFullPrice()
    {
        return self::$variableProductPrefix . self::$variableProductFullPrice;
    }

    public function getProductFunctionArguments()
    {
        $arguments = [
            $this->getVariableProductId(),
            $this->getVariableProductWholesalePrice(),
            $this->getVariableProductFullPrice()
        ];

        return implode(', ', $arguments);
    }

    public function getProductDiscountRuleFunctionArguments()
    {
        $arguments = [
            $this->getVariableProductId(),
            $this->getVariableProductWholesalePrice(),
            $this->getVariableProductFullPrice()
        ];

        return implode(', ', $arguments);
    }

}