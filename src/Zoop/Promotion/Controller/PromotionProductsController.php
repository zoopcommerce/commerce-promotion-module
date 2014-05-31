<?php

namespace Zoop\Promotion\Controller;

use Zoop\Promotion\DataModel\PromotionInterface;

class PromotionProductsController extends AbstractController
{
    public function get($id)
    {
        $promotionCursor = $this->getDm()->createQueryBuilder(self::PROMOTION_DATA_MODEL)
            ->field('registry')->prime(true)
            ->field('id')->equals($id)
            ->field('stores')->in([$this->getStoreSubDomain()])
            ->getQuery()
            ->execute();

        if (!empty($promotionCursor)) {
            foreach ($promotionCursor as $promotion) {
                return $this->getSerializer()->toJson($promotion);
            }
        } else {
            foreach ($promotionCursor as $promotion) {
                return $promotion;
            }
        }

        return false;
    }

    public function getList()
    {
        $this->getSerializer()->setMaxNestingDepth(10);

        $promos = [];
        $promotions = $this->getDm()->createQueryBuilder(self::PROMOTION_DATA_MODEL)
            ->field('stores')->in([$this->getStoreSubDomain()])
            ->getQuery()
            ->execute();

        if (!empty($promotions)) {
            /* @var $promotion PromotionInterface */
            foreach ($promotions as $promotion) {
                $promos[] = $this->getSerializer()->toArray($promotion);
            }
            return json_encode($promos);
        }

        return false;
    }

    public function create($data)
    {

    }

    public function update($id, $data)
    {

    }

    public function remove($id)
    {

    }
}
