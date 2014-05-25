<?php

namespace Zoop\Promotion\Condition\Rule\Cart;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class ShippingType extends AbstractCartRule implements RuleInterface
{

    public function getVariable()
    {
        return $this->getVariableOrderShippingType();
    }

}
