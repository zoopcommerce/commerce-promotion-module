<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductPercentageAmountOff extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ') {
            $discountAmount = (float) (' . $this->getVariableOrderItemTotalSubTotalPrice() . ' * (' . self::RULE_VALUE_VARIABLE . '/100));

            ' . $this->getVariableDiscountClassInstantiation() . '
            ' . $this->getVariableDiscountClassAddItem() . '
            ' . $this->getVariableDiscountClassSetItemDiscount() . '
            ' . $this->getVariableDiscountClassSetIsApplied() . '

            return $discount;
        }';
    }
}
