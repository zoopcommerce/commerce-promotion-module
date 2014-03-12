<?php

namespace Zoop\Promotion\Discount\Rule;

interface RuleInterface
{

    public function getValue();

    public function setValue($value);

    public function getFunction();
}
