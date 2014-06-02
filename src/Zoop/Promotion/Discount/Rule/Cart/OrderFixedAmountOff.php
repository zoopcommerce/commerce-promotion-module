<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class OrderFixedAmountOff extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ') {
            return (float) ((' .
                $this->getVariableOrderProductTotalShippingPrice() .
                ' + (' .
                $this->getVariableOrderProductTotalSubTotalPrice() .
                ')) / ' .
                $this->getVariableOrderTotalPrice() .
                ') * ' . 
                self::RULE_VALUE_VARIABLE .
            ';
        }';
    }
}