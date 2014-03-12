<?php

namespace Zoop\Promotion\DataModel;

use Zoop\Promotion\DataModel\Register\Coupon;
use Zoop\Promotion\DataModel\Register\Finite;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class LimitedPromotion extends AbstractPromotion implements PromotionInterface
{
    /**
     * @ODM\ReferenceMany(
     *      targetDocument="Zoop\Promotion\DataModel\Register\Finite",
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
    protected $limited = true;

    /**
     * @ODM\Int
     */
    protected $limit;

    /**
     * @ODM\Increment
     */
    protected $numberAvailable = 0;

    /**
     * @ODM\Increment
     */
    protected $numberInCart = 0;

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getRegistry()
    {
        return $this->registry;
    }

    public function setRegistry($registry)
    {
        $this->registry = $registry;
    }

    public function addRegister(Finite $register)
    {
        $this->registry[] = $register;
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

    /**
     *
     * @return int
     */
    public function getNumberAvailable()
    {
        return $this->numberAvailable;
    }

    /**
     *
     * @return int
     */
    public function getNumberInCart()
    {
        return $this->numberInCart;
    }

    /**
     *
     * @param int $numberAvailable
     */
    public function setNumberAvailable($numberAvailable)
    {
        $this->numberAvailable = (int) $numberAvailable;
    }

    /**
     * Increments the number used
     */
    public function incrementNumberAvailable()
    {
        $this->numberAvailable++;
    }

    /**
     * Reduces the number used
     */
    public function decrementNumberAvailable()
    {
        $this->numberAvailable--;
    }

    /**
     *
     * @param int $numberInCart
     */
    public function setNumberInCart($numberInCart)
    {
        $this->numberInCart = (int) $numberInCart;
    }

    /**
     * Increments the number used
     */
    public function incrementNumberInCart()
    {
        $this->numberInCart++;
    }

    /**
     * Reduces the number used
     */
    public function decrementNumberInCart()
    {
        $this->numberInCart--;
    }

    public function hasRegisterCoupons()
    {
        $registry = $this->getRegistry();
        if (!empty($registry)) {
            /* @var $register Finite */
            foreach ($registry as $register) {
                /* @var $coupon Coupon */
                $coupon = $register->getCoupon();
                if (!empty($coupon)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getRegisterCount()
    {
        $count = 0;
        $registry = $this->getRegistry();
        if (!empty($registry)) {
            /* @var $register Finite */
            foreach ($registry as $register) {
                $count++;
            }
        }
        return $count;
    }

}
