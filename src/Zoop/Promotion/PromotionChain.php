<?php

namespace Zoop\Promotion;

use \DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Store\DataModel\Store;
use Zoop\Order\DataModel\Order;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Shard\SoftDelete\SoftDeleter;
use Zoop\Shard\Serializer\Serializer;
use Zoop\Shard\Serializer\Unserializer;

class PromotionChain
{
    private $promotions = [];
    private $dm;
    private $softDelete;
    private $serializer;
    private $unserializer;
    private $store;
    private $order;
    private $fetched = false;

    /**
     *
     * @return SoftDeleter
     */
    public function getSoftDelete()
    {
        return $this->softDelete;
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @return Unserializer
     */
    public function getUnserializer()
    {
        return $this->unserializer;
    }

    /**
     * @param SoftDeleter $softDelete
     */
    public function setSoftDelete(SoftDeleter $softDelete)
    {
        $this->softDelete = $softDelete;
    }

    /**
     *
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     *
     * @param Unserializer $unserializer
     */
    public function setUnserializer(Unserializer $unserializer)
    {
        $this->unserializer = $unserializer;
    }

    /**
     *
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->dm;
    }

    /**
     *
     * @param DocumentManager $dm
     */
    public function setDocumentManager(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
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
     * @param Store $store
     */
    public function setStore(Store $store)
    {
        $this->store = $store;
    }

    /**
     *
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    public function getPromotions()
    {
        if (empty($this->promotions)) {
            $this->setPromotions();
        }
        return $this->promotions;
    }

    public function setPromotions($force = false)
    {
        if ((empty($this->promotions) && $this->fetched === false) || $force === true) {
            $this->promotions = [];
            $order = $this->getOrder();

            if ($order instanceof Order) {
                $coupon = $order->getCoupon();
            }

            $qb = $this->getDocumentManager()->createQueryBuilder('Zoop\Promotion\DataModel\AbstractPromotion');
            $qb->addAnd($qb->expr()->field('stores')->in([$this->getStore()->getSubdomain()]));
            $qb->addAnd($qb->expr()->field('active')->equals(true));
            $qb->addAnd($qb->expr()->field('softDeleted')->equals(false));
            //start date restriction
            $qb->addAnd(
                    $qb->expr()->addOr($qb->expr()->field('startDate')->lte(new DateTime))
                            ->addOr($qb->expr()->field('startDate')->exists(false))
            );

            //end date restriction
            $qb->addAnd(
                    $qb->expr()->addOr($qb->expr()->field('endDate')->gte(new DateTime))
                            ->addOr($qb->expr()->field('endDate')->exists(false))
            );

            $qb->addAnd(
                    $qb->expr()->addOr($qb->expr()->field('numberAvailable')->gt(0))
                            ->addOr($qb->expr()->field('numberAvailable')->exists(false))
            );

            //if we have a coupon and order id
            if (!empty($coupon)) {
                $qb->addAnd(
                        $qb->expr()->addOr($qb->expr()->field('couponsMap')->in([$coupon]))
                                ->addOr($qb->expr()->field('couponsMap.0')->exists(false))
                );
            } else {
                $qb->addAnd(
                        $qb->expr()->field('couponsMap.0')->exists(false)
                );
            }
            $qb->sort('allowCombination', 'asc');

            $promotions = $qb->getQuery()->execute();
            foreach ($promotions as $promotion) {
                $this->addPromotion($promotion);
            }

            //add current order promotions
            $promotions = $order->getPromotions();
            foreach ($promotions as $promotion) {
                $this->addPromotion($promotion);
            }

            $this->fetched = true;
        }
    }

    public function addPromotion(PromotionInterface $promotion)
    {
        if (!isset($this->promotions[$promotion->getId()])) {
            $this->promotions[$promotion->getId()] = $promotion;
        }
    }

}
