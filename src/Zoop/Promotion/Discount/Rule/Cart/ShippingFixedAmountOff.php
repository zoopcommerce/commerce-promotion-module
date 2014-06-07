<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ShippingFixedAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalShippingPrice() . ') {
            $discountAmount = (float) ((' .
            $this->getVariableOrderItemTotalShippingPrice() .
            ' / ' .
            $this->getVariableOrderTotalShippingPrice() .
            ') * ' .
            self::RULE_VALUE_VARIABLE .
            ');

            if($discountAmount > ' . $this->getVariableOrderTotalShippingPrice() . ') {
                $discountAmount = (float) ' . $this->getVariableOrderTotalShippingPrice() . ';
            }

            ' . $this->getVariableDiscountClassInstantiation() . '
            ' . $this->getVariableDiscountClassAddItem() . '
            ' . $this->getVariableDiscountClassSetShippingDiscount() . '
            ' . $this->getVariableDiscountClassSetIsApplied() . '

            return $discount;
        }';
    }

}
