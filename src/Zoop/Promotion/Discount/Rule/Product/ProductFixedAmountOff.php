<?php

namespace Zoop\Promotion\Discount\Rule\Product;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductFixedAmountOff extends AbstractProductRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableProductFullPrice() . ') {
            $discountAmount = ' . $this->getVariableProductFullPrice() . ' - ' . self::RULE_VALUE_VARIABLE . ';

            if($discountAmount < 0) {
                $discountAmount = (float) ' . $this->getVariableProductFullPrice() . ';
            }
            
            ' . $this->getVariableDiscountClassInstantiation() . '
            ' . $this->getVariableDiscountClassAddItem() . '
            ' . $this->getVariableDiscountClassSetItemDiscount() . '
            ' . $this->getVariableDiscountClassSetIsApplied() . '

            return $discount;
        }';
    }
}
