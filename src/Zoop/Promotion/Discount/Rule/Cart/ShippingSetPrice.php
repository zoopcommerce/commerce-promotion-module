<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ShippingSetPrice extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderTotalShippingPrice() . ' && ' . $this->getVariableOrderProductShippingPrice() . ') {
                    $discount = ' . $this->getVariableOrderProductShippingPrice() . ' - ((' . $this->getVariableOrderProductShippingPrice() . ' / ' . $this->getVariableOrderTotalShippingPrice() . ') * ' . self::RULE_VALUE_VARIABLE . ');
                    if ($discount < 0) {
                        return (float) ' . $this->getVariableOrderProductShippingPrice() . ';
                    } else {
                        return (float) $discount;
                    }
                }';
    }

}
