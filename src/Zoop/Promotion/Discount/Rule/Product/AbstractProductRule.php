<?php

namespace Zoop\Promotion\Discount\Rule\Product;

use Zoop\Promotion\Discount\Rule\AbstractRule;
use Zoop\Promotion\ProductVariablesTrait;

abstract class AbstractProductRule extends AbstractRule
{

    use ProductVariablesTrait;

    public function __toString()
    {
        $ruleValue = $this->getValue();
        $compiledRule[] = self::RULE_VALUE_VARIABLE . ' = ' . (is_numeric($ruleValue) ? $ruleValue : '"' . $this->getValue() . '"') . ';';
        $compiledRule[] = $this->getFunction();
        $compiledRule[] = 'return 0;';

        return implode("\n", $compiledRule);
    }

}
