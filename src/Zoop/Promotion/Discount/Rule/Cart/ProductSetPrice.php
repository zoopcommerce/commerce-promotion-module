<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductSetPrice extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableOrderProductFullPrice() . ')) {
                    $discount = ' . $this->getVariableOrderProductFullPrice() . ' - ' . self::RULE_VALUE_VARIABLE . ';
                    if($discount < 0) {
                        return (float) ' . $this->getVariableOrderProductFullPrice() . ';
                    } else {
                        return (float) $discount;
                    }
                }';
    }

}
