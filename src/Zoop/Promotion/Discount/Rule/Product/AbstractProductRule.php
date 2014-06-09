<?php

namespace Zoop\Promotion\Discount\Rule\Product;

use Zoop\Promotion\Discount\Rule\AbstractRule;
use Zoop\Promotion\DiscountVariablesTrait;
use Zoop\Promotion\ProductVariablesTrait;

abstract class AbstractProductRule extends AbstractRule
{
    use DiscountVariablesTrait;
    use ProductVariablesTrait;

    public function __toString()
    {
        $ruleValue = $this->getValue();
        $compiledRule[] = self::RULE_VALUE_VARIABLE . ' = ' . (is_numeric($ruleValue) ? $ruleValue : '"' . $this->getValue() . '"') . ';';
        $compiledRule[] = $this->getFunction();
        $compiledRule[] = 'return new '. $this->getVariableDiscountClass() . ';';

        return implode("\n", $compiledRule);
    }
}
