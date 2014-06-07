<?php

namespace Zoop\Promotion\Condition\Rule\Cart;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class ProductPrice extends AbstractCartRule implements RuleInterface
{
    public function getVariable()
    {
        return $this->getVariableOrderItemTotalSubTotalPrice();
    }
}
