<?php

namespace Zoop\Promotion\Test\Rules;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Discount\Rule\Cart;
use Zoop\Promotion\CartVariablesTrait;
use Zoop\Order\DataModel\Order;
use Zoop\Order\DataModel\Total;
use Zoop\Order\DataModel\Item\SingleItem;
use Zoop\Order\DataModel\Item\PhysicalSku;
use Zoop\Order\DataModel\Item\Price;

class CartRuleTest extends AbstractTest
{
    use CartVariablesTrait;
    const RULE_FIXED_VALUE = 10;
    const RULE_PERCENTAGE_VALUE = 10;
    const RULE_SET_PRICE = 10;

    public function testOrderFixedAmountOff()
    {
        $rule = new Cart\OrderFixedAmountOff;
        $rule->setValue(self::RULE_FIXED_VALUE);
        $function = $this->createCartFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(10, $discount);
    }

    public function testOrderPercentageAmountOff()
    {
        $rule = new Cart\OrderPercentageAmountOff;
        $rule->setValue(self::RULE_PERCENTAGE_VALUE);
        $function = $this->createCartFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(12, $discount);
    }

    public function testProductFixedAmountOff()
    {
        $rule = new Cart\ProductFixedAmountOff;
        $rule->setValue(self::RULE_FIXED_VALUE);
        $function = $this->createCartFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(20, $discount);
    }

    public function testProductPercentageAmountOff()
    {
        $rule = new Cart\ProductPercentageAmountOff;
        $rule->setValue(self::RULE_PERCENTAGE_VALUE);
        $function = $this->createCartFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(10, $discount);
    }

    public function testProductSetPrice()
    {
        $rule = new Cart\ProductSetPrice;
        $rule->setValue(self::RULE_SET_PRICE);
        $function = $this->createCartFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(80, $discount);
    }

    public function testProductWholesalePrice()
    {
        $rule = new Cart\ProductWholesalePrice;
        $function = $this->createCartFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(50, $discount);
    }

    public function testShippingSetPrice()
    {
        $rule = new Cart\ShippingSetPrice;
        $rule->setValue(self::RULE_SET_PRICE);
        $function = $this->createCartFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(10, $discount);
    }

    protected function getDiscount($function)
    {
        return $function($this->createOrder());
    }

    protected function compileFunction($code)
    {
        $function[] = $this->getVariableConditionalProducts() . '=[];';
        $function[] = '$discount=0;';
        $function[] = 'foreach(' . $this->getVariableOrderProducts() . ' as ' . $this->getVariableOrderProductPrefix() . ') {';
        $function[] = '$functions[] = ' . $this->createCartSubFunction($code);
        $function[] = '}';

        $function[] = 'foreach($functions as $function) {';
        $function[] = '$discount += $function();';
        $function[] = '}';
        $function[] = 'return $discount;';

        return implode("\n", $function);
    }

    protected function createCartSubFunction($code)
    {
        $function[] = 'function() use (' . $this->getCartDiscountRuleFunctionArguments() . ') {';
        $function[] = $code;
        $function[] = '};';

        return implode("\n", $function);
    }

    protected function createCartFunction($code)
    {
        return create_function(
            $this->getCartFunctionArguments(),
            $this->compileFunction($code)
        );
    }

    protected function createOrder()
    {
        $order = new Order;
        $order->setEmail('test@email.com');
        $order->setState('in-progress');
        $order->setStore($this->getStore());
        $order->setLegacyId(1);

        $total = new Total;
        $total->setShippingPrice(5);
        $total->setProductWholesalePrice(15);
        $total->setProductListPrice(25);
        $total->setTaxIncluded(2);
        $total->setProductQuantity(1);
        $total->setDiscountPrice(0);
        $total->setOrderPrice(20);

        $order->setTotal($total);

        $sku = new PhysicalSku;

        $price = new Price;
        $price->setWholesale(15);
        $price->setList(25);
        $price->setShipping(5);
        $price->setDiscount(0);

        $item = new SingleItem;
        $item->setBrand('Some brand');
        $item->setSku($sku);
        $item->setPrice($price);
        $item->setName('Some name');
        $item->setLegacyId(1);

        $order->addItem($item);
        
        return $order;
    }
}
