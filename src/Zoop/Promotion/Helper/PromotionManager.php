<?php

namespace Zoop\Promotion\Helper;

use \DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Store\DataModel\Store;
use Zoop\Order\DataModel\OrderInterface;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Promotion\Helper\PromotionManagerInterface;

class PromotionManager implements PromotionManagerInterface
{
    private $promotions = [];
    private $dm;
    private $store;
    private $fetched = false;
    
    public function __construct(DocumentManager $dm, Store $store)
    {
        $this->setDocumentManager($dm);
        $this->setStore($store);
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
     * @param Store $store
     */
    public function setStore(Store $store)
    {
        $this->store = $store;
    }

    public function get(OrderInterface $order)
    {
        $cacheId = spl_object_hash($order);
        
        if (!isset($this->promotions[$cacheId]) && $this->fetched === false) {
            $this->promotions[$cacheId] = [];
            
            $coupon = $order->getCoupon();

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
                $this->addPromotion($cacheId, $promotion);
            }

            //add current order promotions
            $promotions = $order->getPromotions();
            foreach ($promotions as $promotion) {
                $this->addPromotion($cacheId, $promotion);
            }

            $this->fetched = true;
        }
        
        return $this->promotions[$cacheId];
    }
    
    public function addPromotion($cacheId, PromotionInterface $promotion)
    {
        if (!isset($this->promotions[$cacheId][$promotion->getId()])) {
            $this->promotions[$cacheId][$promotion->getId()] = $promotion;
        }
    }
    
    public function clear()
    {
        $this->fetched = false;
        $this->promotions = [];
    }
}
