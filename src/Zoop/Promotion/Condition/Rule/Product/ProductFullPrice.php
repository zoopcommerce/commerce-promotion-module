<?php

namespace Zoop\Promotion\Condition\Rule\Product;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class ProductFullPrice extends AbstractProductRule implements RuleInterface
{

    public function getVariable()
    {
        return $this->getVariableProductFullPrice();
    }

}
