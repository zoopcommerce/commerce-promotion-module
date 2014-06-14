<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductFixedAmountOff extends AbstractCartRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalPrice() . ') {
                $discountAmount = (float) (' . self::RULE_VALUE_VARIABLE . ' * ' . $this->getVariableOrderItemQuantity() . ');
                $discounted = ' . $this->getVariableOrderItemTotalSubTotalPrice() . ' - $discountAmount;' .

                'if($discounted < 0) {' .
                    '$discountAmount = (float) ' . $this->getVariableOrderItemTotalSubTotalPrice() . ';' .
                '}

                ' . $this->getVariableCartDiscountClassInstantiation() . '
                ' . $this->getVariableDiscountClassSetItem() . '
                ' . $this->getVariableDiscountClassSetItemDiscount() . '
                ' . $this->getVariableDiscountClassSetIsApplied() . '

                return $discount;
            }';
    }
}
