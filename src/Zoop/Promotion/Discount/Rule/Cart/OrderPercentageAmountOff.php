<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class OrderPercentageAmountOff extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ') {
                $discountAmount = (float) ((' .
                $this->getVariableOrderItemTotalSubTotalPrice() .
                ' + ' .
                $this->getVariableOrderItemTotalShippingPrice() .
                ') * (' .
                self::RULE_VALUE_VARIABLE .
                '/100));

                ' . $this->getVariableDiscountClassInstantiation() . '
                ' . $this->getVariableDiscountClassAddItem() . '
                ' . $this->getVariableDiscountClassSetCartDiscount() . '
                ' . $this->getVariableDiscountClassSetIsApplied() . '
                return $discount;
            }';
    }
}
