<?php

namespace Zoop\Promotion\Discount\Rule\Product;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductWholesalePrice extends AbstractProductRule implements RuleInterface
{
    public function getFunction()
    {
        return 'if (' . $this->getVariableProductFullPrice() . ' && ' . $this->getVariableProductWholesalePrice() . ') {
            $discountAmount = (float) (' . $this->getVariableProductFullPrice() . ' - ' . $this->getVariableProductWholesalePrice() . ');
            
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
