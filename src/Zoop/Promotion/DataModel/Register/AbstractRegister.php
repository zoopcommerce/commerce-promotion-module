<?php

namespace Zoop\Promotion\DataModel\Register;

use \DateTime;
use Zoop\Shard\Stamp\DataModel\CreatedOnTrait;
use Zoop\Shard\SoftDelete\DataModel\SoftDeleteableTrait;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document(collection="PromotionRegistry")
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({
 *     "finite"       = "Zoop\Promotion\DataModel\Register\Finite",
 *     "infinite"     = "Zoop\Promotion\DataModel\Register\Infinite"
 * })
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
abstract class AbstractRegister
{
//    use CreatedOnTrait;
    use SoftDeleteableTrait;

    const STATE_AVAILABLE = 'available';

    /**
     * @ODM\Date
     */
    protected $createdOn;

    /**
     * @ODM\Date
     */
    protected $updatedOn;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @return DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param DateTime $createdOn
     */
    public function setCreatedOn(DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param DateTime $updatedOn
     */
    public function setUpdatedOn(DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
}
