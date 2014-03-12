<?php

namespace Zoop\Promotion\Condition\Rule\Cart;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class ShippingCountry extends AbstractCartRule implements RuleInterface
{

    public function getVariable()
    {
        return $this->getVariableCartShippingCountry();
    }

}
