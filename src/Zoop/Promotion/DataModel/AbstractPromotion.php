<?php

namespace Zoop\Promotion\DataModel;

use \DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Zoop\Order\DataModel\Order;
use Zoop\Store\DataModel\Store;
use Zoop\Promotion\DataModel\Discount\DiscountInterface;
use Zoop\Shard\Stamp\DataModel\CreatedOnTrait;
use Zoop\Shard\Stamp\DataModel\UpdatedOnTrait;
use Zoop\Shard\SoftDelete\DataModel\SoftDeleteableTrait;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document(
 *      collection="Promotion",
 *      indexes={
 *          @ODM\Index(keys={"active"="asc"}),
 *          @ODM\Index(keys={"stores"="asc"}),
 *          @ODM\Index(keys={"startDate"="asc"}),
 *          @ODM\Index(keys={"endDate"="asc"}),
 *          @ODM\Index(keys={"couponsMap"="asc"}),
 *          @ODM\Index(keys={"allowCombination"="asc"})
 *      }
 * )
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField("type")
 * @ODM\DiscriminatorMap({
 *     "Limited"         = "Zoop\Promotion\DataModel\LimitedPromotion",
 *     "Unlimited"       = "Zoop\Promotion\DataModel\UnlimitedPromotion"
 * })
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
abstract class AbstractPromotion
{
    use CreatedOnTrait;
    use UpdatedOnTrait;
    use SoftDeleteableTrait;

    const TYPE_LIMITED = 'Limited';
    const TYPE_UNLIMITED = 'Unlimited';

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Int
     */
    protected $legacyId;

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
     * @ODM\String
     */
    protected $name;

    /**
     * @ODM\Date
     * @ODM\Index
     */
    protected $startDate;

    /**
     * @ODM\Date
     * @ODM\Index
     */
    protected $endDate;

    /**
     * @ODM\Increment
     */
    protected $numberUsed = 0;

    /**
     * @ODM\Collection
     */
    protected $productIds = [];

    /**
     * @ODM\EmbedMany(
     *  discriminatorField="type",
     *  discriminatorMap={
     *     "FixedAmountOff"         = "Zoop\Promotion\DataModel\Discount\FixedAmountOff",
     *     "PercentageAmountOff"    = "Zoop\Promotion\DataModel\Discount\PercentageAmountOff",
     *     "SetPrice"               = "Zoop\Promotion\DataModel\Discount\SetPrice",
     *     "WholesalePrice"         = "Zoop\Promotion\DataModel\Discount\WholesalePrice"
     *   }
     * )
     */
    protected $discounts;

    /**
     * @ODM\Collection
     * @ODM\Index
     */
    protected $couponsMap = [];

    /**
     * @ODM\String
     */
    protected $cartFunction;

    /**
     * @ODM\String
     */
    protected $productFunction;

    /**
     * @ODM\Boolean
     */
    protected $allowCombination = true;

    /**
     * @ODM\ReferenceMany(
     *      targetDocument="Zoop\Order\DataModel\Order",
     *      mappedBy="promotions"
     * )
     */
    protected $orders;

    /**
     * @ODM\Boolean
     * @ODM\Index
     */
    protected $active = true;

    public function __construct()
    {
        $this->discounts = new ArrayCollection;
        $this->orders = new ArrayCollection;
    }

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return int
     */
    public function getLegacyId()
    {
        return $this->legacyId;
    }

    /**
     *
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
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     *
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     *
     * @return int
     */
    public function getNumberUsed()
    {
        return $this->numberUsed;
    }

    /**
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     *
     * @param int $legacyId
     */
    public function setLegacyId($legacyId)
    {
        $this->legacyId = (int) $legacyId;
    }

    /**
     *
     * @param array $stores
     */
    public function setStores(array $stores)
    {
        $this->stores = $stores;
    }

    /**
     *
     * @param Store $store
     */
    public function addStore(Store $store)
    {
        $this->stores[] = $store->getId();
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @param DateTime $startDate
     */
    public function setStartDate(DateTime $startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     *
     * @param DateTime $endDate
     */
    public function setEndDate(DateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     *
     * @param int $numberUsed
     */
    public function setNumberUsed($numberUsed)
    {
        $this->numberUsed = (int) $numberUsed;
    }

    /**
     * Increments the number used
     */
    public function incrementNumberUsed()
    {
        $this->numberUsed++;
    }

    /**
     * Reduces the number used
     */
    public function decrementNumberUsed()
    {
        $this->numberUsed--;
    }

    /**
     *
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = (boolean) $active;
    }

    /**
     *
     * @return array
     */
    public function getProductIds()
    {
        return $this->productIds;
    }

    /**
     *
     * @param array $productIds
     */
    public function setProductIds($productIds)
    {
        $this->productIds = $productIds;
    }

    /**
     *
     * @param int $productId
     */
    public function addProductId($productId)
    {
        if (is_array($this->productIds) && !in_array($productId, $this->productIds) && !in_array(0, $this->productIds)) {
            $this->productIds[] = (int) $productId;
        }
    }

    /**
     *
     * @return array
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     *
     * @param array $discounts
     */
    public function setDiscounts(array $discounts)
    {
        $this->discounts = $discounts;
    }

    /**
     *
     * @param DiscountInterface $discount
     */
    public function addDiscount(DiscountInterface $discount)
    {
        $this->discounts[] = $discount;
    }

    /**
     *
     * @return array
     */
    public function getCouponsMap()
    {
        return $this->couponsMap;
    }

    /**
     *
     * @param array $couponsMap
     */
    public function setCouponsMap(array $couponsMap)
    {
        $this->couponsMap = $couponsMap;
    }

    /**
     *
     * @param string $coupon
     */
    public function addCouponToMap($coupon)
    {
        if (!in_array($coupon, $this->couponsMap)) {
            $this->couponsMap[] = $coupon;
        }
    }

    /**
     *
     * @return boolean
     */
    public function hasCoupons()
    {
        $coupons = $this->getCouponsMap();
        if (!empty($coupons) && count($coupons) > 0) {
            return true;
        }
        return false;
    }

    /**
     *
     * @return string
     */
    public function getCartFunction()
    {
        return $this->cartFunction;
    }

    /**
     *
     * @return string
     */
    public function getProductFunction()
    {
        return $this->productFunction;
    }

    /**
     *
     * @param string $cartFunction
     */
    public function setCartFunction($cartFunction)
    {
        $this->cartFunction = $cartFunction;
    }

    /**
     *
     * @param string $productFunction
     */
    public function setProductFunction($productFunction)
    {
        $this->productFunction = $productFunction;
    }

    /**
     *
     * @return boolean
     */
    public function getAllowCombination()
    {
        return $this->allowCombination;
    }

    /**
     *
     * @param boolean $allowCombination
     */
    public function setAllowCombination($allowCombination)
    {
        $this->allowCombination = (boolean) $allowCombination;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     *
     * @param Order $order
     */
    public function addOrder(Order $order)
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
        }
    }
}
