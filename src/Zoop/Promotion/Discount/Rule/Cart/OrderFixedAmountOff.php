<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class OrderFixedAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableOrderTotalPrice() . ')) {
            return (float) ((' . $this->getVariableOrderProductShippingPrice() . ' + (' . $this->getVariableOrderProductFullPrice() . ' * ' . $this->getVariableOrderProductQuantity() . ')) / ' . $this->getVariableOrderTotalPrice() . ') * ' . self::RULE_VALUE_VARIABLE . ';
        }';
    }

}
