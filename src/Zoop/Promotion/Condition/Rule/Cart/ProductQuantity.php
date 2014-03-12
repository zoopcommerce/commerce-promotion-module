<?php

namespace Zoop\Promotion\Condition\Rule\Cart;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class ProductQuantity extends AbstractCartRule implements RuleInterface
{

    public function getVariable()
    {
        return $this->getVariableCartProductQuantity();
    }

}
