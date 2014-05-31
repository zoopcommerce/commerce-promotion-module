<?php

namespace Zoop\Promotion\DataModel\Register;

use \DateTime;
use Zoop\Shard\Stamp\DataModel\CreatedOnTrait;
use Zoop\Shard\SoftDelete\DataModel\SoftDeleteableTrait;
use Zoop\Store\DataModel\Store;
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
    use CreatedOnTrait;
    use SoftDeleteableTrait;

    const STATE_AVAILABLE = 'available';

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Date
     */
    protected $updatedOn;
    
    /**
     * Array. Stores that this product is part of.
     * The Zones annotation means this field is used by the Zones filter so
     * only products from the active store are available.
     *
     * @ODM\Collection
     * @ODM\Index
     * @Shard\Validator\Required
     */
    protected $stores = [];

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
     * @param array $stores
     */
    public function setStores(array $stores)
    {
        $this->stores = $stores;
    }

    /**
     * @param Store $store
     */
    public function addStore(Store $store)
    {
        $this->stores[] = $store->getId();
    }

    /**
     * @return array
     */
    public function getStores()
    {
        return $this->stores;
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
