<?php

namespace Zoop\Promotion\Condition\Rule;

abstract class AbstractRule
{

    const TYPE_VARIABLE = 'variable';
    const TYPE_FUNCTION = 'function';

    protected $value;
    protected $type = self::TYPE_VARIABLE;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function __toString()
    {
        return $this->getVariable();
    }

}
