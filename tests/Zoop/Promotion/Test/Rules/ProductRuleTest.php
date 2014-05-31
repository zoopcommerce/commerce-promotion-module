<?php

namespace Zoop\Promotion\Test\Rules;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Discount\Rule\Product;
use Zoop\Promotion\ProductVariablesTrait;

class ProductRuleTest extends AbstractTest
{

    use ProductVariablesTrait;
    const RULE_FIXED_VALUE = 10;
    const RULE_PERCENTAGE_VALUE = 10;
    const RULE_SET_PRICE = 10;

    protected static $product = [
        'id' => 1,
        'wholesale' => 50,
        'fullePrice' => 80,
    ];

    public function testProductFixedAmountOff()
    {

        $rule = new Product\ProductFixedAmountOff;
        $rule->setValue(self::RULE_FIXED_VALUE);

        $function = $this->createProductFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(10, $discount);
    }

    public function testProductPercentageAmountOff()
    {

        $rule = new Product\ProductPercentageAmountOff;
        $rule->setValue(self::RULE_PERCENTAGE_VALUE);

        $function = $this->createProductFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(8, $discount);
    }

    public function testProductSetPrice()
    {

        $rule = new Product\ProductSetPrice;
        $rule->setValue(self::RULE_SET_PRICE);

        $function = $this->createProductFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(70, $discount);
    }

    public function testProductWholesalePrice()
    {

        $rule = new Product\ProductWholesalePrice;
        $function = $this->createProductFunction((string) $rule);

        $discount = $this->getDiscount($function);

        $this->assertEquals(30, $discount);
    }

    protected function getDiscount($function)
    {
        return $function(
            self::$product['id'],
            self::$product['wholesale'],
            self::$product['fullePrice']
        );
    }

    protected function createProductFunction($code)
    {
        return create_function($this->getProductFunctionArguments(), $code);
    }

}
