<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductFixedAmountOff extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ') {
            $discount = ' . $this->getVariableOrderProductTotalSubTotalPrice() . ' - ' . self::RULE_VALUE_VARIABLE . ';' .
            'if($discount < 0) {' . 
            'return (float) ' . $this->getVariableOrderProductTotalSubTotalPrice() . ';' .
            '} else {' .
            'return (float) $discount;' .
            '}' .
            '}';
    }
}
