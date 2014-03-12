<?php

namespace Zoop\Promotion\Condition\Rule\Cart;

use Zoop\Promotion\Condition\Rule\RuleInterface;

class OrderQuantity extends AbstractCartRule implements RuleInterface
{

    public function __construct()
    {
        $this->setType(self::TYPE_FUNCTION);
    }

    public function getVariable()
    {
        return 'function() use (' . $this->getVariableCartProducts() . ', ' . $this->getVariableConditionalProducts() . ') {
                    $quantity = 0;
                    foreach(' . $this->getVariableCartProducts() . ' as ' . $this->getVariableCartProductPrefix() . ') {
                        if(in_array(' . $this->getVariableCartProductId() . ',' . $this->getVariableConditionalProducts() . ')) {
                            $quantity += ' . $this->getVariableCartProductQuantity() . ';
                        }
                    }
                    return $quantity;
                }';
    }

}
