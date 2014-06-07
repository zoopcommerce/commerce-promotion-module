<?php

namespace Zoop\Promotion;

trait CartVariablesTrait
{
    protected static $variableOrderPrefix = '$order';
    protected static $variableOrderTotalQuantity = '->getTotal()->getProductQuantity()';
    protected static $variableOrderTotalPrice = '->getTotal()->getOrderPrice()';
    protected static $variableOrderTotalWholesalePrice = '->getTotal()->getProductWholesalePrice()';
    protected static $variableOrderTotalProductListPrice = '->getTotal()->getProductListPrice()';
    protected static $variableOrderTotalProductSubTotalPrice = '->getTotal()->getProductSubTotalPrice()';
    protected static $variableOrderTotalDiscountPrice = '->getTotal()->getDiscountPrice()';
    protected static $variableOrderTotalShippingPrice = '->getTotal()->getShippingPrice()';
    protected static $variableOrderShippingType = '->getShippingMethod()';
    protected static $variableOrderShippingCountry = '->getAddress()->getCountry()';
    protected static $variableOrderItems = '->getItems()';
    protected static $variableOrderItem = '$item';
    protected static $variableOrderItemId = '->getLegacyId()';
    protected static $variableOrderItemUnitListPrice = '->getPrice()->getUnit()->getList()';
    protected static $variableOrderItemUnitProductDiscountPrice = '->getPrice()->getUnit()->getProductDiscount()';
    protected static $variableOrderItemUnitCartDiscountPrice = '->getPrice()->getUnit()->getCartDiscount()';
    protected static $variableOrderItemUnitShippingDiscountPrice = '->getPrice()->getUnit()->getShippingDiscount()';
    protected static $variableOrderItemUnitWholesalePrice = '->getPrice()->getUnit()->getWholesale()';
    protected static $variableOrderItemUnitShippingPrice = '->getPrice()->getUnit()->getShipping()';
    protected static $variableOrderItemUnitSalePrice = '->getPrice()->getUnit()->getSale()';
    protected static $variableOrderItemUnitSubTotalPrice = '->getPrice()->getUnit()->getSubTotal()';
    protected static $variableOrderItemTotalListPrice = '->getPrice()->getTotal()->getList()';
    protected static $variableOrderItemTotalProductDiscountPrice = '->getPrice()->getTotal()->getProductDiscount()';
    protected static $variableOrderItemTotalCartDiscountPrice = '->getPrice()->getTotal()->getCartDiscount()';
    protected static $variableOrderItemTotalShippingDiscountPrice = '->getPrice()->getTotal()->getShippingDiscount()';
    protected static $variableOrderItemTotalWholesalePrice = '->getPrice()->getTotal()->getWholesale()';
    protected static $variableOrderItemTotalShippingPrice = '->getPrice()->getTotal()->getShipping()';
    protected static $variableOrderItemTotalSalePrice = '->getPrice()->getTotal()->getSale()';
    protected static $variableOrderItemTotalSubTotalPrice = '->getPrice()->getTotal()->getSubTotal()';
    protected static $variableOrderItemQuantity = '->getQuantity()';
    protected static $variableConditionalProducts = '$conditionalProducts';

    public static function getVariableOrder()
    {
        return self::$variableOrderPrefix;
    }

    public static function getVariableOrderTotalQuantity()
    {
        return self::$variableOrderPrefix . self::$variableOrderTotalQuantity;
    }

    public static function getVariableOrderTotalPrice()
    {
        return self::$variableOrderPrefix . self::$variableOrderTotalPrice;
    }

    public static function getVariableOrderTotalWholesalePrice()
    {
        return self::$variableOrderPrefix . self::$variableOrderTotalWholesalePrice;
    }

    public static function getVariableOrderTotalProductListPrice()
    {
        return self::$variableOrderPrefix . self::$variableOrderTotalProductListPrice;
    }

    public static function getVariableOrderTotalProductSubTotalPrice()
    {
        return self::$variableOrderPrefix . self::$variableOrderTotalProductSubTotalPrice;
    }

    public static function getVariableOrderTotalDiscountPrice()
    {
        return self::$variableOrderPrefix . self::$variableOrderTotalDiscountPrice;
    }

    public static function getVariableOrderTotalShippingPrice()
    {
        return self::$variableOrderPrefix . self::$variableOrderTotalShippingPrice;
    }

    public static function getVariableOrderShippingType()
    {
        return self::$variableOrderPrefix . self::$variableOrderShippingType;
    }

    public static function getVariableOrderShippingCountry()
    {
        return self::$variableOrderPrefix . self::$variableOrderShippingCountry;
    }

    public static function getVariableOrderItems()
    {
        return self::$variableOrderPrefix . self::$variableOrderItems;
    }

    public static function getVariableOrderItem()
    {
        return self::$variableOrderItem;
    }

    public static function getVariableOrderItemId()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemId;
    }

    public static function getVariableOrderItemTotalListPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemTotalListPrice;
    }

    public static function getVariableOrderItemTotalProductDiscountPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemTotalProductDiscountPrice;
    }

    public static function getVariableOrderItemTotalCartDiscountPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemTotalCartDiscountPrice;
    }

    public static function getVariableOrderItemTotalShippingDiscountPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemTotalShippingDiscountPrice;
    }

    public static function getVariableOrderItemTotalWholesalePrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemTotalWholesalePrice;
    }

    public static function getVariableOrderItemTotalShippingPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemTotalShippingPrice;
    }

    public static function getVariableOrderItemTotalSalePrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemTotalSalePrice;
    }

    public static function getVariableOrderItemTotalSubTotalPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemTotalSubTotalPrice;
    }

    public static function getVariableOrderItemUnitListPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemUnitListPrice;
    }

    public static function getVariableOrderItemUnitProductDiscountPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemUnitProductDiscountPrice;
    }

    public static function getVariableOrderItemUnitCartDiscountPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemUnitCartDiscountPrice;
    }

    public static function getVariableOrderItemUnitShippingDiscountPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemUnitShippingDiscountPrice;
    }

    public static function getVariableOrderItemUnitWholesalePrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemUnitWholesalePrice;
    }

    public static function getVariableOrderItemUnitShippingPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemUnitShippingPrice;
    }

    public static function getVariableOrderItemUnitSalePrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemUnitSalePrice;
    }

    public static function getVariableOrderItemUnitSubTotalPrice()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemUnitSubTotalPrice;
    }

    public static function getVariableOrderItemQuantity()
    {
        return self::getVariableOrderItem() . self::$variableOrderItemQuantity;
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
            $this->getVariableOrderItem(),
        ];

        return implode(', ', $arguments);
    }
}
