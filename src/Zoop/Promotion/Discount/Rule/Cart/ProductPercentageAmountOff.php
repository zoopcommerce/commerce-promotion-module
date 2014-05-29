<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductPercentageAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (' . $this->getVariableOrderProductFullPrice() . ') {
                    return (float) (' . $this->getVariableOrderProductFullPrice() . ' * (' . self::RULE_VALUE_VARIABLE . '/100));
                }';
    }

}
