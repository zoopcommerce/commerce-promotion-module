<?php

namespace Zoop\Promotion\Discount\Rule\Cart;

use Zoop\Promotion\Discount\Rule\RuleInterface;

class ProductFixedAmountOff extends AbstractCartRule implements RuleInterface
{

    public function getFunction()
    {
        return 'if(' . $this->getVariableOrderProductFullPrice() . '){
                    $discount = ' . $this->getVariableOrderProductFullPrice() . ' - ' . self::RULE_VALUE_VARIABLE . ';

                    if($discount < 0) {
                        return (float) ' . $this->getVariableOrderProductFullPrice() . ';
                    } else {
                        return (float) ' . self::RULE_VALUE_VARIABLE . ';
                    }
                }';
    }

}
