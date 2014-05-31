<?php

namespace Zoop\Promotion\Helper;

use \DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Store\DataModel\Store;
use Zoop\Order\DataModel\Order;
use Zoop\Promotion\DataModel\PromotionInterface;

class PromotionManager
{
    private $promotions;
    private $dm;
    private $store;
    private $order;
    private $fetched = false;
    
    public function __construct(DocumentManager $dm, Store $store, Order $order)
    {
        $this->dm = $dm;
        $this->store = $store;
        $this->order = $order;
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
        if (!isset($this->promotions)) {
            $this->fetch();
        }
        return $this->promotions;
    }

    protected function fetch()
    {
        if (empty($this->promotions) && $this->fetched === false) {
            $this->promotions = [];
            
            $order = $this->getOrder();

            if ($order instanceof Order) {
                $coupon = $order->getCoupon();
            }

            $qb = $this->getDocumentManager()
                ->createQueryBuilder('Zoop\Promotion\DataModel\AbstractPromotion');

            $qb->addAnd($qb->expr()->field('stores')->in([$this->getStore()->getId()]));
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
