<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductWholesalePrice extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableCartProductFullPrice() . ') && !empty(' . $this->getVariableCartProductWholesalePrice() . ')) {
                    $discount = ' . $this->getVariableCartProductFullPrice() . ' - ' . $this->getVariableCartProductWholesalePrice() . ';
                    if ($discount < 0) {
                        return (float) ' . $this->getVariableCartProductFullPrice() . ';
                    } else {
                        return (float) $discount;
                    }
                }';
    }

}
