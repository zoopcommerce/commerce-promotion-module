<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ShippingFixedAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableCartTotalShippingPrice() . ')) {
            $discount = (float) (' . $this->getVariableCartProductShippingPrice() . ' / ' . $this->getVariableCartTotalShippingPrice() . ') * ' . self::RULE_VALUE_VARIABLE . ';
            if($discount > ' . $this->getVariableCartTotalShippingPrice() . ') {
                return ' . $this->getVariableCartTotalShippingPrice() . ';
            }
            return $discount;
        }';
    }

}
