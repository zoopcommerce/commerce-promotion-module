<?php

namespace Zoop\Promotion\DataModel\Condition\Operator\Logical;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\EmbeddedDocument
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class OrOperator extends AbstractOperator implements LogicalInterface
{

    /**
     * @ODM\String
     */
    protected $operator = '||';

}
