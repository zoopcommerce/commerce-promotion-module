<?php

namespace Zoop\Promotion\DataModel\Register;

use Doctrine\Common\Collections\ArrayCollection;
use Zoop\Order\DataModel\Order;
use Zoop\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Promotion\DataModel\Register\Coupon;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class Infinite extends AbstractRegister implements RegisterInterface
{
    /**
     * @ODM\ReferenceOne(
     *      targetDocument="Zoop\Promotion\DataModel\UnlimitedPromotion",
     *      inversedBy="registry"
     * )
     */
    protected $promotion;

    /**
     * @ODM\String
     * @Shard\State({
     *      "available"
     * })
     */
    protected $state = self::STATE_AVAILABLE;

    /**
     * @ODM\EmbedMany(targetDocument="Zoop\Promotion\DataModel\Register\Coupon")
     */
    protected $coupons;

    public function __construct()
    {
        $this->coupons = new ArrayCollection;
        $this->orders = new ArrayCollection;
    }

    /**
     *
     * @return UnlimitedPromotion
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     *
     * @param UnlimitedPromotion $promotion
     */
    public function setPromotion(UnlimitedPromotion $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     *
     * @return array
     */
    public function getCoupons()
    {
        return $this->coupons;
    }

    /**
     *
     * @param array $coupons
     */
    public function setCoupons($coupons)
    {
        $this->coupons = $coupons;
    }

    /**
     *
     * @param Coupon $coupon
     */
    public function addCoupon(Coupon $coupon)
    {
        $this->coupons[] = $coupon;
    }

}
