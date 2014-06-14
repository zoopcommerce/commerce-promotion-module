<?php

namespace Zoop\Promotion\Test\Discount;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Discount\ProductDiscount;
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

        $discount = $this->getDiscount($product, $function);

        $this->assertTrue($discount instanceof ProductDiscount);
        $this->assertTrue($discount->isApplied());
        $this->assertEquals(10, $discount->getItemDiscount());
        $this->assertEquals(10, $discount->getTotalDiscount());
    }

    public function testProductPercentageAmountOff()
    {
        $product = self::createSingleProduct();
        
        $rule = new Product\ProductPercentageAmountOff;
        $rule->setValue(10);

        $function = $this->createProductFunction((string) $rule);

        $discount = $this->getDiscount($product, $function);

        $this->assertTrue($discount instanceof ProductDiscount);
        $this->assertTrue($discount->isApplied());
        $this->assertEquals(109.9, $discount->getItemDiscount());
        $this->assertEquals(109.9, $discount->getTotalDiscount());
    }

    public function testProductSetPrice()
    {
        $product = self::createSingleProduct();
        
        $rule = new Product\ProductSetPrice;
        $rule->setValue(1000);

        $function = $this->createProductFunction((string) $rule);

        $discount = $this->getDiscount($product, $function);

        $this->assertTrue($discount instanceof ProductDiscount);
        $this->assertTrue($discount->isApplied());
        $this->assertEquals(99, $discount->getItemDiscount());
        $this->assertEquals(99, $discount->getTotalDiscount());
    }

    public function testProductWholesalePrice()
    {
        $product = self::createSingleProduct();
        
        $rule = new Product\ProductWholesalePrice;

        $function = $this->createProductFunction((string) $rule);

        $discount = $this->getDiscount($product, $function);

        $this->assertTrue($discount instanceof ProductDiscount);
        $this->assertTrue($discount->isApplied());
        $this->assertEquals(599, $discount->getItemDiscount());
        $this->assertEquals(599, $discount->getTotalDiscount());
    }

    protected function getDiscount(ProductInterface $product, $function)
    {
        return $function($product);
    }

    protected function createProductFunction($code)
    {
        return create_function($this->getProductFunctionArguments(), $code);
    }
}