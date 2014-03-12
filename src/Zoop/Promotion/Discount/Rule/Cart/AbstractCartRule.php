<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\AbstractRule;
use Zoop\Promotion\CartVariablesTrait;

abstract class AbstractCartRule extends AbstractRule
{

    use CartVariablesTrait;

    public function __toString()
    {
        $ruleValue = $this->getValue();
        $compiledRule[] = self::RULE_VALUE_VARIABLE . ' = ' . (is_numeric($ruleValue) ? $ruleValue : '"' . $this->getValue() . '"') . ';';
        $compiledRule[] = $this->getFunction();
        $compiledRule[] = 'return 0;';

        return implode("\n", $compiledRule);
    }

}
