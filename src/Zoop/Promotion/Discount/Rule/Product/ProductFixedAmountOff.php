<?php

namespace Zoop\Promotion\Discount\Rule\Product;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductFixedAmountOff extends AbstractProductRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if (!empty(' . $this->getVariableProductFullPrice() . ')){
                    $discount = ' . $this->getVariableProductFullPrice() . ' - ' . self::RULE_VALUE_VARIABLE . ';

                    if($discount < 0) {
                        return (float) ' . $this->getVariableProductFullPrice() . ';
                    } else {
                        return (float) ' . self::RULE_VALUE_VARIABLE . ';
                    }
                }';
    }

}
