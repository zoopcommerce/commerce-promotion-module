<?php

namespace Zoop\Promotion\DataModel\Condition;

use Zoop\Promotion\DataModel\Condition\Operator\Conditional\Equal;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\EmbeddedDocument
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
abstract class AbstractStringCondition extends AbstractCondition
{

    /**
     * @ODM\EmbedOne(
     *  discriminatorField     = "type",
     *  discriminatorMap={
     *      "Equal"            = "Zoop\Promotion\DataModel\Condition\Operator\Conditional\Equal"
     *  }
     * )
     */
    protected $conditionalOperator;

    public function __construct()
    {
        $this->setConditionalOperator(new Equal);
    }

    /**
     * @ODM\String
     */
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
        $this->value = (string) $value;
    }

}
