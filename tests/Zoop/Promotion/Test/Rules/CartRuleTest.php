<?php

namespace Zoop\Promotion\Test\Rules;

use Zoop\Promotion\Test\BaseTest;
use Zoop\Promotion\Discount\Rule\Cart;
use Zoop\Promotion\CartVariablesTrait;

class CartRuleTest extends BaseTest
{

    use CartVariablesTrait;
    const RULE_FIXED_VALUE = 10;
    const RULE_PERCENTAGE_VALUE = 10;
    const RULE_SET_PRICE = 10;

    protected static $cart = [
        'totalQuantity' => 2,
        'totalPrice' => 120,
        'totalProductPrice' => 100,
        'totalWholesalePrice' => 50,
        'totalDiscountPrice' => 0,
        'totalShippingPrice' => 20,
        'shippingType' => 'Regular',
        'shippingCountry' => 'AU',
        'products' => [
            [
                'productId' => 101,
                'fullPrice' => 60,
                'totalShipping' => 15,
                'wholesalePrice' => 30,
                'quantity' => 1
            ],
            [
                'productId' => 102,
                'fullPrice' => 40,
                'totalShipping' => 5,
                'wholesalePrice' => 20,
                'quantity' => 1
            ]
        ]
    ];

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
        return $function(
            self::$cart['totalQuantity'],
            self::$cart['totalPrice'],
            self::$cart['totalWholesalePrice'],
            self::$cart['totalProductPrice'],
            self::$cart['totalDiscountPrice'],
            self::$cart['totalShippingPrice'],
            self::$cart['shippingType'],
            self::$cart['shippingCountry'],
            self::$cart['products']
        );
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

}
