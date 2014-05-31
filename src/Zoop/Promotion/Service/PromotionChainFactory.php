<?php

namespace Zoop\Promotion\Service;

use Zoop\Promotion\PromotionChain;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PromotionChainFactory implements FactoryInterface
{
    /**
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return PromotionChain
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dm = $serviceLocator->get('shard.commerce.modelmanager');

        $manifest = $serviceLocator->get('shard.commerce.manifest');
        /* @var $manifest \Zoop\Shard\Manifest */
        $unserializer = $manifest->getServiceManager()->get('unserializer');
        $serializer = $manifest->getServiceManager()->get('serializer');
        $softDelete = $manifest->getServiceManager()->get('softdeleter');

        $order = $serviceLocator->get('zoop.commerce.order.active');
        $store = $serviceLocator->get('zoop.commerce.store.active');

        $promotionChain = new PromotionChain;

        $promotionChain->setDocumentManager($dm);
        $promotionChain->setSerializer($serializer);
        $promotionChain->setUnserializer($unserializer);
        $promotionChain->setSoftDelete($softDelete);

        $promotionChain->setStore($store);
        $promotionChain->setOrder($order);

        return $promotionChain;
    }
}
