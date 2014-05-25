<?php

namespace Zoop\Promotion\Condition\Rule\Cart;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class ProductFullPrice extends AbstractCartRule implements RuleInterface
{

    public function getVariable()
    {
        return $this->getVariableOrderProductFullPrice();
    }

}
