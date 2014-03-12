<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductSetPrice extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableCartProductFullPrice() . ')) {
                    $discount = ' . $this->getVariableCartProductFullPrice() . ' - ' . self::RULE_VALUE_VARIABLE . ';
                    if($discount < 0) {
                        return (float) ' . $this->getVariableCartProductFullPrice() . ';
                    } else {
                        return (float) $discount;
                    }
                }';
    }

}
