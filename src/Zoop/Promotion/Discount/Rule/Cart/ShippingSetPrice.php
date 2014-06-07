<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ShippingSetPrice extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderItemTotalSubTotalPrice() . ' && ' . $this->getVariableOrderItemTotalShippingPrice() . ') {
            $discountAmount = (float) (' .
            $this->getVariableOrderItemTotalShippingPrice() .
            ' - ((' .
            $this->getVariableOrderItemTotalShippingPrice() .
            ' / ' .
            $this->getVariableOrderTotalShippingPrice() .
            ') * ' .
            self::RULE_VALUE_VARIABLE .
            '));
                
            if ($discountAmount < 0) {
                return (float) ' . $this->getVariableOrderItemTotalShippingPrice() . ';
            }
            
            ' . $this->getVariableDiscountClassInstantiation() . '
            ' . $this->getVariableDiscountClassAddItem() . '
            ' . $this->getVariableDiscountClassSetShippingDiscount() . '
            ' . $this->getVariableDiscountClassSetIsApplied() . '
                
            return $discount;
        }';
    }
}
