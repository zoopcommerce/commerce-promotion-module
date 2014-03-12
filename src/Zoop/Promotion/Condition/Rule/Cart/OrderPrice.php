<?php

namespace Zoop\Promotion\Condition\Rule\Cart;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class OrderPrice extends AbstractCartRule implements RuleInterface
{

    public function getVariable()
    {
        return $this->getVariableCartTotalPrice();
    }

}
