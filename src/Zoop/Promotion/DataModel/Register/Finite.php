<?php

namespace Zoop\Promotion\DataModel\Register;

use \DateTime;
use Zoop\Order\DataModel\Order;
use Zoop\Promotion\DataModel\LimitedPromotion;
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
class Finite extends AbstractRegister implements RegisterInterface
{
    const STATE_IN_CART = 'in-cart';
    const STATE_USED = 'used';

    /**
     * @ODM\ReferenceOne(
     *      targetDocument="Zoop\Promotion\DataModel\LimitedPromotion",
     *      inversedBy="registry"
     * )
     */
    protected $promotion;

    /**
     * @ODM\ReferenceOne(
     *      targetDocument="Zoop\Order\DataModel\Order",
     *      inversedBy="promotions"
     * )
     */
    protected $order;

    /**
     * @ODM\EmbedOne(targetDocument="Zoop\Promotion\DataModel\Register\Coupon")
     */
    protected $coupon;

    /**
     * @ODM\String
     * @Shard\State({
     *      "available",
     *      "in-cart",
     *      "used"
     * })
     */
    protected $state = self::STATE_AVAILABLE;

    /**
     * @ODM\Date
     */
    protected $stateExpiry;

    /**
     *
     * @return LimitedPromotion
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     *
     * @param LimitedPromotion $promotion
     */
    public function setPromotion(LimitedPromotion $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     *
     * @return DateTime
     */
    public function getStateExpiry()
    {
        return $this->stateExpiry;
    }

    /**
     *
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     *
     * @param DateTime $stateExpiry
     */
    public function setStateExpiry(DateTime $stateExpiry)
    {
        $this->stateExpiry = $stateExpiry;
    }

    /**
     *
     * @return Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     *
     * @param Coupon $coupon
     */
    public function setCoupon(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }
}
