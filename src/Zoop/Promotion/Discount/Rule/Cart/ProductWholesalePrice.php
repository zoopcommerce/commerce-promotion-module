<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductWholesalePrice extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderProductFullPrice() . ' && ' . $this->getVariableOrderProductWholesalePrice() . ') {
                    $discount = ' . $this->getVariableOrderProductFullPrice() . ' - ' . $this->getVariableOrderProductWholesalePrice() . ';
                    if ($discount < 0) {
                        return (float) ' . $this->getVariableOrderProductFullPrice() . ';
                    } else {
                        return (float) $discount;
                    }
                }';
    }

}
