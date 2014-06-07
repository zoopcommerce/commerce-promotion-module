<?php

namespace Zoop\Promotion\Test\Rules;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Discount\Rule\Cart;
use Zoop\Promotion\Discount\Discount;
use Zoop\Promotion\Discount\DiscountChain;
use Zoop\Promotion\CartVariablesTrait;
use Zoop\Promotion\DiscountVariablesTrait;
use Zoop\Order\DataModel\OrderInterface;

class CartRuleTest extends AbstractTest
{
    use CartVariablesTrait;
    use DiscountVariablesTrait;

    public function testOrderFixedAmountOff()
    {
        $rule = new Cart\OrderFixedAmountOff;
        $rule->setValue(10);
        $function = $this->createCartFunction((string) $rule);

        $order = self::createOrder();
        $discountChain = $this->getDiscount($order, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(10, $discountChain->getTotalDiscount());
        $this->assertEquals(10, $discountChain->getTotalCartDiscount());
        $this->assertEquals(0, $discountChain->getTotalItemDiscount());
        $this->assertEquals(0, $discountChain->getTotalShippingDiscount());
        
        $this->assertEquals(4.7641509433962, $discounts[0]->getTotalDiscount());
        $this->assertEquals(5.2358490566038, $discounts[1]->getTotalDiscount());
    }

    public function testOrderPercentageAmountOff()
    {
        $rule = new Cart\OrderPercentageAmountOff;
        $rule->setValue(10);
        $function = $this->createCartFunction((string) $rule);

        $order = self::createOrder();
        $discountChain = $this->getDiscount($order, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(212, $discountChain->getTotalDiscount());
        $this->assertEquals(212, $discountChain->getTotalCartDiscount());
        $this->assertEquals(0, $discountChain->getTotalItemDiscount());
        $this->assertEquals(0, $discountChain->getTotalShippingDiscount());
        
        $this->assertEquals(101, $discounts[0]->getTotalDiscount());
        $this->assertEquals(111, $discounts[1]->getTotalDiscount());
    }

    public function testProductFixedAmountOff()
    {
        $rule = new Cart\ProductFixedAmountOff;
        $rule->setValue(10);
        $function = $this->createCartFunction((string) $rule);

        $order = self::createOrder();
        $discountChain = $this->getDiscount($order, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(30, $discountChain->getTotalDiscount());
        $this->assertEquals(0, $discountChain->getTotalCartDiscount());
        $this->assertEquals(30, $discountChain->getTotalItemDiscount());
        $this->assertEquals(0, $discountChain->getTotalShippingDiscount());
        
        $this->assertEquals(10, $discounts[0]->getTotalDiscount());
        $this->assertEquals(20, $discounts[1]->getTotalDiscount());
    }

    public function testProductPercentageAmountOff()
    {
        $rule = new Cart\ProductPercentageAmountOff;
        $rule->setValue(10);
        $function = $this->createCartFunction((string) $rule);
        
        $order = self::createOrder();
        $discountChain = $this->getDiscount($order, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(210, $discountChain->getTotalDiscount());
        $this->assertEquals(0, $discountChain->getTotalCartDiscount());
        $this->assertEquals(210, $discountChain->getTotalItemDiscount());
        $this->assertEquals(0, $discountChain->getTotalShippingDiscount());
        
        $this->assertEquals(100, $discounts[0]->getTotalDiscount());
        $this->assertEquals(110, $discounts[1]->getTotalDiscount());
    }

    public function testProductSetPrice()
    {
        $rule = new Cart\ProductSetPrice;
        $rule->setValue(500);
        $function = $this->createCartFunction((string) $rule);
        
        $order = self::createOrder();
        $discountChain = $this->getDiscount($order, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(1100, $discountChain->getTotalDiscount());
        $this->assertEquals(0, $discountChain->getTotalCartDiscount());
        $this->assertEquals(1100, $discountChain->getTotalItemDiscount());
        $this->assertEquals(0, $discountChain->getTotalShippingDiscount());
        
        $this->assertEquals(500, $discounts[0]->getTotalDiscount());
        $this->assertEquals(600, $discounts[1]->getTotalDiscount());
    }

    public function testProductWholesalePrice()
    {
        $rule = new Cart\ProductWholesalePrice;
        $function = $this->createCartFunction((string) $rule);

        $order = self::createOrder();
        $discountChain = $this->getDiscount($order, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(800, $discountChain->getTotalDiscount());
        $this->assertEquals(0, $discountChain->getTotalCartDiscount());
        $this->assertEquals(800, $discountChain->getTotalItemDiscount());
        $this->assertEquals(0, $discountChain->getTotalShippingDiscount());
        
        $this->assertEquals(500, $discounts[0]->getTotalDiscount());
        $this->assertEquals(300, $discounts[1]->getTotalDiscount());
    }

    public function testShippingFixedAmountOff()
    {
        $rule = new Cart\ShippingFixedAmountOff;
        $rule->setValue(12);
        $function = $this->createCartFunction((string) $rule);

        $order = self::createOrder();
        $discountChain = $this->getDiscount($order, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(12, $discountChain->getTotalDiscount());
        $this->assertEquals(0, $discountChain->getTotalCartDiscount());
        $this->assertEquals(0, $discountChain->getTotalItemDiscount());
        $this->assertEquals(12, $discountChain->getTotalShippingDiscount());
        
        $this->assertEquals(6, $discounts[0]->getTotalDiscount());
        $this->assertEquals(6, $discounts[1]->getTotalDiscount());
    }

    public function testShippingSetPrice()
    {
        $rule = new Cart\ShippingSetPrice;
        $rule->setValue(10);
        $function = $this->createCartFunction((string) $rule);

        $order = self::createOrder();
        $discountChain = $this->getDiscount($order, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(10, $discountChain->getTotalDiscount());
        $this->assertEquals(0, $discountChain->getTotalCartDiscount());
        $this->assertEquals(0, $discountChain->getTotalItemDiscount());
        $this->assertEquals(10, $discountChain->getTotalShippingDiscount());
        
        $this->assertEquals(5, $discounts[0]->getTotalDiscount());
        $this->assertEquals(5, $discounts[1]->getTotalDiscount());
    }

    protected function getDiscount(OrderInterface $order, $function)
    {
        return $function($order);
    }

    protected function compileFunction($code)
    {
        $function[] = $this->getVariableConditionalProducts() . '=[];';
        $function[] = $this->getVariableDiscountChainClassInstantiation();
        $function[] = 'foreach(' . $this->getVariableOrderItems() . ' as ' . $this->getVariableOrderItem() . ') {';
        $function[] = '$functions[] = ' . $this->createCartSubFunction($code);
        $function[] = '}';

        $function[] = 'foreach($functions as $function) {';
        $function[] = '$discount = $function();';
        $function[] = $this->getVariableDiscountChainClassAddDiscount();
        $function[] = '}';
        $function[] = 'return $discountChain;';

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
        $function = $this->compileFunction($code);

        return create_function($this->getCartFunctionArguments(), $function);
    }
}