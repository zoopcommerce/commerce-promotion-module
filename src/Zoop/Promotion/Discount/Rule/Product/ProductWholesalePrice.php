<?php

namespace Zoop\Promotion\Discount\Rule\Product;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductWholesalePrice extends AbstractProductRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableProductFullPrice() . ') && !empty(' . $this->getVariableProductWholesalePrice() . ')) {
                    $discount = ' . $this->getVariableProductFullPrice() . ' - ' . $this->getVariableProductWholesalePrice() . ';

                    if ($discount < 0) {
                        return (float) ' . $this->getVariableProductFullPrice() . ';
                    } else {
                        return (float) $discount;
                    }
                }';
    }

}
