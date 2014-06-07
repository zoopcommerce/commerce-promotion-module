<?php

namespace Zoop\Promotion\Discount;

use \Exception;
use \ReflectionClass;
use Zoop\Promotion\DiscountVariablesTrait;
use Zoop\Promotion\CartVariablesTrait;
use Zoop\Promotion\ProductVariablesTrait;
use Zoop\Promotion\DataModel\Condition\ConditionInterface;
use Zoop\Promotion\DataModel\Discount\DiscountInterface;
use Zoop\Promotion\Discount\Rule\RuleInterface;
use Zoop\Promotion\Condition\Compiler as ConditionCompiler;
use Zoop\Promotion\DataModel\Discount\Variable;
use Zoop\Promotion\DataModel\Condition;

class Compiler
{
    use CartVariablesTrait;
    use DiscountVariablesTrait;
    use ProductVariablesTrait;

    const DISCOUNT_LEVEL_CART = 'Cart';
    const DISCOUNT_LEVEL_PRODUCT = 'Product';
    const RULE_CLASSNAME = 'Zoop\Promotion\Discount\Rule\%s\%s';
    const VARIABLE_DISCOUNT_APPLIED = '$discountApplied';

    private $discount;

    /**
     *
     * @return DiscountInterface
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     *
     * @param DiscountInterface $discount
     */
    public function setDiscount(DiscountInterface $discount)
    {
        $this->discount = $discount;

        $this->setDiscountLevel($discount);
    }

    public function compile()
    {
        $discount = $this->getDiscount();

        $tempCompiledDiscount = [];
        $condition = $this->getCompiledConditions($discount);

        $tempCompiledDiscount[] = $this->getDiscountHeader();

        //add the conditional functions
        if (!empty($condition)) {
            $discountRule = self::VARIABLE_DISCOUNT_APPLIED . ' = true;';
            $discountRule .= $this->getDiscountRule($discount);
            $tempCompiledDiscount[] = sprintf($condition, $discountRule);
        } else {
            $tempCompiledDiscount[] = self::VARIABLE_DISCOUNT_APPLIED . ' = true;';
            $tempCompiledDiscount[] = $this->getDiscountRule($discount);
        }

        $tempCompiledDiscount[] = $this->getDiscountFooter();

        return implode("\n", $tempCompiledDiscount);
    }

    private function getDiscountHeader()
    {
        if ($this->getDiscount()->getLevel() === self::DISCOUNT_LEVEL_CART) {
            $header[] = 'function() use (' . $this->getCartDiscountRuleFunctionArguments() . ') {';
        } elseif ($this->getDiscount()->getLevel() === self::DISCOUNT_LEVEL_PRODUCT) {
            $header[] = 'function() use (' . $this->getProductDiscountRuleFunctionArguments() . ') {';
        }

        return implode("\n", $header);
    }

    private function getDiscountFooter()
    {
        $footer[] = 'return new ' . $this->getVariableDiscountClass() . ';';
        $footer[] = '};';

        return implode("\n", $footer);
    }

    private function getCompiledConditions(DiscountInterface $discount)
    {
        $compiler = new ConditionCompiler();
        $compiler->setDiscount($discount);

        return $compiler->compile();
    }

    private function setDiscountLevel(DiscountInterface $discount)
    {
        $level = self::DISCOUNT_LEVEL_CART;
        $value = $discount->getVariable();

        if ($value instanceof Variable\Product) {
            $level = self::DISCOUNT_LEVEL_PRODUCT;

            $conditions = $discount->getConditions();
            if (!empty($conditions)) {
                /* @var $condtion ConditionInterface */
                foreach ($conditions as $condition) {
                    if (!$condition instanceof Condition\ProductPrice) {
                        $level = self::DISCOUNT_LEVEL_CART;
                    }
                }
            }
        }

        $discount->setLevel($level);
    }

    /**
     *
     * @param DiscountInterface $discount
     * @return RuleInterface
     */
    private function getDiscountRule(DiscountInterface $discount)
    {
        $variableClassName = $this->getClassName($discount->getVariable());
        $discountClassName = $this->getClassName($discount);

        $level = $discount->getLevel();
        if (!empty($level)) {
            $ruleClassName = $variableClassName . $discountClassName;

            $class = sprintf(self::RULE_CLASSNAME, $level, $ruleClassName);

            $ruleClass = $this->instantiateRuleClass($class);
            if ($ruleClass instanceof RuleInterface) {
                $ruleClass->setValue($discount->getValue());
                return $ruleClass;
            }
        }
    }

    private function getClassName($className)
    {
        $className = explode('\\', get_class($className));
        return $className[count($className) - 1];
    }

    /**
     *
     * @param string $class
     * @return RuleInterface
     */
    private function instantiateRuleClass($class)
    {
        try {
            $ref = new ReflectionClass($class);
            return $ref->newInstance();
        } catch (Exception $ex) {
            die('Could not instantiate discount: ' . $ex->getMessage());
        }
    }
}
