<?php

namespace Zoop\Promotion\Discount\Rule;

abstract class AbstractRule
{
    const RULE_VALUE_VARIABLE = '$ruleValue';

    protected $value;

    /**
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
