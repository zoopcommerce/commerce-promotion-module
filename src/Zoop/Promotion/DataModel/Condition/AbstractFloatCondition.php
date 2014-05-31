<?php

namespace Zoop\Promotion\DataModel\Condition;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\EmbeddedDocument
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
abstract class AbstractFloatCondition extends AbstractCondition
{
    /**
     * @ODM\Float
     */
    protected $value;

    /**
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     * @param float $value
     */
    public function setValue($value)
    {
        $this->value = (float) $value;
    }
}
