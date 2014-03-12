<?php

namespace Zoop\Promotion\Discount\Rule\Product;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductPercentageAmountOff extends AbstractProductRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableProductFullPrice() . ')) {
                    return (float) (' . $this->getVariableProductFullPrice() . ' * (' . self::RULE_VALUE_VARIABLE . '/100));
                }';
    }

}
