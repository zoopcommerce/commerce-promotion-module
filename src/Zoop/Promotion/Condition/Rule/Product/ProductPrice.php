<?php

namespace Zoop\Promotion\Condition\Rule\Product;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class ProductPrice extends AbstractProductRule implements RuleInterface
{
    public function getVariable()
    {
        return $this->getVariableProductFullPrice();
    }
}
