<?php

namespace Zoop\Promotion\Condition\Rule\Cart;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class ShippingPrice extends AbstractCartRule implements RuleInterface
{
    public function getVariable()
    {
        return $this->getVariableOrderTotalShippingPrice();
    }
}
