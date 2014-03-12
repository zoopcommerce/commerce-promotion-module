<?php

namespace Zoop\Promotion\Condition\Rule;

interface RuleInterface
{

    public function getVariable();

    public function getType();

    public function setType($type);
}
