<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\AbstractRule;
use Zoop\Promotion\CartVariablesTrait;
use Zoop\Promotion\DiscountVariablesTrait;

abstract class AbstractCartRule extends AbstractRule
{
    use DiscountVariablesTrait;
    use CartVariablesTrait;

    public function __toString()
    {
        $ruleValue = $this->getValue();
        $compiledRule[] = self::RULE_VALUE_VARIABLE . ' = ' . (is_numeric($ruleValue) ? $ruleValue : '"' . $this->getValue() . '"') . ';';
        $compiledRule[] = $this->getFunction();
        $compiledRule[] = 'return new '. $this->getVariableDiscountClass() . ';';

        return implode("\n", $compiledRule);
    }
}
