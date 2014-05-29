<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ShippingFixedAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalShippingPrice() . ') {
            $discount = (float) (' . $this->getVariableOrderProductShippingPrice() . ' / ' . $this->getVariableOrderTotalShippingPrice() . ') * ' . self::RULE_VALUE_VARIABLE . ';
            if($discount > ' . $this->getVariableOrderTotalShippingPrice() . ') {
                return ' . $this->getVariableOrderTotalShippingPrice() . ';
            }
            return $discount;
        }';
    }

}
