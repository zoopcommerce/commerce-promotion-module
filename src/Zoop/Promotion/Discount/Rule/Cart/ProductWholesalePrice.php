<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductWholesalePrice extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ' && ' . $this->getVariableOrderItemTotalWholesalePrice() . ') {
            $discountAmount = (float) (' . $this->getVariableOrderItemTotalSubTotalPrice() . ' - ' . $this->getVariableOrderItemTotalWholesalePrice() . ');

            if ($discountAmount < 0) {
                $discountAmount = (float) ' . $this->getVariableOrderItemTotalSubTotalPrice() . ';
            }

            ' . $this->getVariableCartDiscountClassInstantiation() . '
            ' . $this->getVariableDiscountClassSetItem() . '
            ' . $this->getVariableDiscountClassSetItemDiscount() . '
            ' . $this->getVariableDiscountClassSetIsApplied() . '

            return $discount;
        }';
    }
}
