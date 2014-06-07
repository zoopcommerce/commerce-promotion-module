<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductSetPrice extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ') {
            $discountAmount = (float) (' . $this->getVariableOrderItemTotalSubTotalPrice() . ' - ' . self::RULE_VALUE_VARIABLE . ');

            if($discountAmount < 0) {
                return (float) ' . $this->getVariableOrderItemTotalSubTotalPrice() . ';
            }

            ' . $this->getVariableDiscountClassInstantiation() . '
            ' . $this->getVariableDiscountClassAddItem() . '
            ' . $this->getVariableDiscountClassSetItemDiscount() . '
            ' . $this->getVariableDiscountClassSetIsApplied() . '

            return $discount;
        }';
    }
}
