<?php

namespace Zoop\Promotion\DataModel\Discount\Variable;

//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\EmbeddedDocument
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class Order extends AbstractVariable implements VariableInterface
{

    /**
     * @ODM\String
     */
    protected $name = 'Order';

}
