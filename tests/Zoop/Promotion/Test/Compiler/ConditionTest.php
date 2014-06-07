<?php

namespace Zoop\Promotion\Test\Compiler;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\DataModel\Condition\Operator\Conditional;
use Zoop\Promotion\DataModel\Condition\Operator\Logical;
use Zoop\Promotion\DataModel\Condition;
use Zoop\Promotion\DataModel\Discount;
use Zoop\Promotion\DataModel\Discount\Variable;
use Zoop\Promotion\Condition\Compiler as ConditionCompiler;
use Zoop\Promotion\Discount\Compiler as DiscountCompiler;

class ConditionTest extends AbstractTest
{
    public function testEmptyCondition()
    {
        $compiler = new ConditionCompiler;

        $discount = new Discount\PercentageAmountOff;
        $discount->setValue(15);
        $discount->setVariable(new Variable\Order);
        $compiler->setDiscount($discount);

        $compiled = $compiler->compile();

        $this->assertFalse($compiled);
    }

    public function testCompiledSingleCondition()
    {
        $compiler = new ConditionCompiler;

        $discount = new Discount\PercentageAmountOff;
        $discount->setLevel(DiscountCompiler::DISCOUNT_LEVEL_PRODUCT);
        $discount->setValue(15);
        $discount->setVariable(new Variable\Product);

        $condition = new Condition\ProductPrice;
        $condition->setConditionalOperator(new Conditional\GreaterThan);
        $condition->setValue(100);
        $discount->addCondition($condition);

        $compiler->setDiscount($discount);
        $compiled = str_replace("\n", "", $compiler->compile());

        $expected = 'if($product->getPrice()->getFull() > 100) {%s}';

        $this->assertEquals($expected, $compiled);
    }

    public function testCompiledMultipleCondition()
    {

        $compiler = new ConditionCompiler;

        $discount = new Discount\PercentageAmountOff;
        $discount->setLevel(DiscountCompiler::DISCOUNT_LEVEL_PRODUCT);
        $discount->setValue(15);
        $discount->setVariable(new Variable\Product);

        $condition = new Condition\ProductPrice;
        $condition->setConditionalOperator(new Conditional\GreaterThan);
        $condition->setLogicalOperator(new Logical\AndOperator);
        $condition->setValue(100);
        $discount->addCondition($condition);

        $condition = new Condition\ProductPrice;
        $condition->setConditionalOperator(new Conditional\LessThan);
        $condition->setLogicalOperator(new Logical\AndOperator);
        $condition->setValue(200);
        $discount->addCondition($condition);

        $compiler->setDiscount($discount);
        $compiled = str_replace("\n", "", $compiler->compile());

        $expected = 'if($product->getPrice()->getFull() > 100 && $product->getPrice()->getFull() < 200) {%s}';

        $this->assertEquals($expected, $compiled);
    }
}
