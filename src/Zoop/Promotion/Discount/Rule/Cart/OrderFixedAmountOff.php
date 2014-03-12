<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class OrderFixedAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableCartTotalPrice() . ')) {
            return (float) ((' . $this->getVariableCartProductShippingPrice() . ' + (' . $this->getVariableCartProductFullPrice() . ' * ' . $this->getVariableCartProductQuantity() . ')) / ' . $this->getVariableCartTotalPrice() . ') * ' . self::RULE_VALUE_VARIABLE . ';
        }';
    }

}
