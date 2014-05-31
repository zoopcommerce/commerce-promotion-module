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
abstract class AbstractIntegerCondition extends AbstractCondition
{
    /**
     * @ODM\Int
     */
    protected $value;

    /**
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = (int) $value;
    }
}
