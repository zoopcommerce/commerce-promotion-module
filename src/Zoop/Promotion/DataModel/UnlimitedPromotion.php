<?php

namespace Zoop\Promotion\DataModel;

use Zoop\Promotion\DataModel\Register\Infinite;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class UnlimitedPromotion extends AbstractPromotion implements PromotionInterface
{
    /**
     * @ODM\ReferenceOne(
     *      targetDocument="Zoop\Promotion\DataModel\Register\Infinite",
     *      simple="true",
     *      mappedBy="promotion"
     * )
     * @Shard\Serializer\Ignore
     * @Shard\Unserializer\Ignore
     */
    protected $registry;

    /**
     * @ODM\Boolean
     */
    protected $limited = false;

    /**
     *
     * @return Infinite
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     *
     * @return boolean
     */
    public function getLimited()
    {
        return $this->limited;
    }

    /**
     *
     * @param boolean $limited
     */
    public function setLimited($limited)
    {
        $this->limited = (boolean) $limited;
    }

    public function hasRegisterCoupons()
    {
        $register = $this->getRegistry();

        if (!empty($register)) {
            $coupons = $register->getCoupons();
            if (!empty($coupons)) {
                return true;
            }
        }
        return false;
    }

    public function getRegisterCount()
    {
        $count = 0;
        $register = $this->getRegistry();
        if (!empty($register)) {
            $count++;
        }
        return $count;
    }
}
