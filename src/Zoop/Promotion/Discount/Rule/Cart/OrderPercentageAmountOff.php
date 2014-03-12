<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class OrderPercentageAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'return ((' . $this->getVariableCartProductFullPrice() . ' * (int) ' . $this->getVariableCartProductQuantity() . ') + ' . $this->getVariableCartProductShippingPrice() . ') * (' . self::RULE_VALUE_VARIABLE . '/100);';
    }

}
