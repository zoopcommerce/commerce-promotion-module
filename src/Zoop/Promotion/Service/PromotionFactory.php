<?php

namespace Zoop\Promotion\Service;

use Zoop\Promotion\Promotion;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PromotionFactory implements FactoryInterface
{
    /**
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Promotion
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dm = $serviceLocator->get('shard.commerce.modelmanager');
        $order = $serviceLocator->get('zoop.commerce.order.legacy.model.active');
        $promotionChain = $serviceLocator->get('zoop.commerce.promotion.chain');

        $promotion = new Promotion;
        $promotion->setDocumentManager($dm);
        $promotion->setOrder($order);
        $promotion->setPromotionChain($promotionChain);

        return $promotion;
    }
}
