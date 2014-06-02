<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class OrderPercentageAmountOff extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ') {
            return (float) ((' .
            $this->getVariableOrderProductTotalSubTotalPrice() .
            ' + ' .
            $this->getVariableOrderProductTotalShippingPrice() .
            ') * (' .
            self::RULE_VALUE_VARIABLE .
            '/100));' . 
            '}';
    }
}
