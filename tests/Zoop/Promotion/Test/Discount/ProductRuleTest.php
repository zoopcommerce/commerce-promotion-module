<?php

namespace Zoop\Promotion\Test\Discount;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Discount\Rule\Product;
use Zoop\Promotion\ProductVariablesTrait;
use Zoop\Product\DataModel\ProductInterface;

class ProductRuleTest extends AbstractTest
{
    use ProductVariablesTrait;

    public function testProductFixedAmountOff()
    {
        $product = self::createSingleProduct();
        
        $rule = new Product\ProductFixedAmountOff;
        $rule->setValue(10);

        $function = $this->createProductFunction((string) $rule);

        $discountChain = $this->getDiscount($product, $function);

        $this->assertTrue($discountChain instanceof DiscountChain);
        $discounts = $discountChain->getDiscounts();
        $this->assertCount(2, $discounts);
        $this->assertEquals(10, $discountChain->getTotalDiscount());
    }
//
//    public function testProductPercentageAmountOff()
//    {
//
//        $rule = new Product\ProductPercentageAmountOff;
//        $rule->setValue(self::RULE_PERCENTAGE_VALUE);
//
//        $function = $this->createProductFunction((string) $rule);
//
//        $discount = $this->getDiscount($product, $function);
//
//        $this->assertEquals(8, $discount);
//    }
//
//    public function testProductSetPrice()
//    {
//
//        $rule = new Product\ProductSetPrice;
//        $rule->setValue(self::RULE_SET_PRICE);
//
//        $function = $this->createProductFunction((string) $rule);
//
//        $discount = $this->getDiscount($product, $function);
//
//        $this->assertEquals(70, $discount);
//    }
//
//    public function testProductWholesalePrice()
//    {
//
//        $rule = new Product\ProductWholesalePrice;
//        $function = $this->createProductFunction((string) $rule);
//
//        $discount = $this->getDiscount($product, $function);
//
//        $this->assertEquals(30, $discount);
//    }

    protected function getDiscount(ProductInterface $product, $function)
    {
        return $function($product);
    }

    protected function createProductFunction($code)
    {
        return create_function($this->getProductFunctionArguments(), $code);
    }
}
