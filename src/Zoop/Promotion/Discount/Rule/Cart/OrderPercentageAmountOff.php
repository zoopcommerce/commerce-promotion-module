<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class OrderPercentageAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'return ((' . $this->getVariableOrderProductFullPrice() . ' * (int) ' . $this->getVariableOrderProductQuantity() . ') + ' . $this->getVariableOrderProductShippingPrice() . ') * (' . self::RULE_VALUE_VARIABLE . '/100);';
    }

}
