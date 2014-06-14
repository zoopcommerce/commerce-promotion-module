<?php

namespace Zoop\Promotion\Discount\Rule\Product;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductSetPrice extends AbstractProductRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableProductFullPrice() . ') {
            $discountAmount = (float) (' . $this->getVariableProductFullPrice() . ' - ' . self::RULE_VALUE_VARIABLE . ');
            
            if($discountAmount < 0) {
                return (float) ' . $this->getVariableProductFullPrice() . ';
            }
            
            ' . $this->getVariableProductDiscountClassInstantiation() . '
            ' . $this->getVariableDiscountClassSetProduct() . '
            ' . $this->getVariableDiscountClassSetItemDiscount() . '
            ' . $this->getVariableDiscountClassSetIsApplied() . '

            return $discount;
        }';
    }
}
