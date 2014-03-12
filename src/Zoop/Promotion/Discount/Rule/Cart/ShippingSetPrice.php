<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ShippingSetPrice extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableCartTotalShippingPrice() . ') && !empty(' . $this->getVariableCartProductShippingPrice() . ')) {
                    $discount = ' . $this->getVariableCartProductShippingPrice() . ' - ((' . $this->getVariableCartProductShippingPrice() . ' / ' . $this->getVariableCartTotalShippingPrice() . ') * ' . self::RULE_VALUE_VARIABLE . ');
                    if ($discount < 0) {
                        return (float) ' . $this->getVariableCartProductShippingPrice() . ';
                    } else {
                        return (float) $discount;
                    }
                }';
    }

}
