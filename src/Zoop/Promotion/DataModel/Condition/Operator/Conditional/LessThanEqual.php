<?php

namespace Zoop\Promotion\DataModel\Condition\Operator\Conditional;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\EmbeddedDocument
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class LessThanEqual extends AbstractOperator implements ConditionalInterface
{

    /**
     * @ODM\String
     */
    protected $operator = '<=';

}
