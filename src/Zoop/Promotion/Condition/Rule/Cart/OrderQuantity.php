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
        return 'function() use (' . $this->getVariableOrderProducts() . ', ' . $this->getVariableConditionalProducts() . ') {
                    $quantity = 0;
                    foreach(' . $this->getVariableOrderProducts() . ' as ' . $this->getVariableOrderProductPrefix() . ') {
                        if(in_array(' . $this->getVariableOrderProductId() . ',' . $this->getVariableConditionalProducts() . ')) {
                            $quantity += ' . $this->getVariableOrderProductQuantity() . ';
                        }
                    }
                    return $quantity;
                }';
    }
}
