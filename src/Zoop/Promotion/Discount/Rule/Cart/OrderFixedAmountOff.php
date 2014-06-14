<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class OrderFixedAmountOff extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ') {
            $discountAmount = (float) ((' .
                $this->getVariableOrderItemTotalShippingPrice() .
                ' + (' .
                $this->getVariableOrderItemTotalSubTotalPrice() .
                ')) / ' .
                $this->getVariableOrderTotalPrice() .
                ') * ' .
                self::RULE_VALUE_VARIABLE . ';

            ' . $this->getVariableCartDiscountClassInstantiation() . '
            ' . $this->getVariableDiscountClassSetItem() . '
            ' . $this->getVariableDiscountClassSetCartDiscount() . '
            ' . $this->getVariableDiscountClassSetIsApplied() . '

            return $discount;
        }';
    }
}
