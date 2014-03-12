<?php

namespace Zoop\Promotion\Service;

use Zoop\Promotion\Controller\PromotionProductsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PromotionProductsControllerFactory implements FactoryInterface
{
    /**
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return PromotionProductsController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $store = $serviceLocator->get('zoop.commerce.store.legacy.active');
        $manifest = $serviceLocator->get('shard.commerce.manifest');

        $controller = new PromotionProductsController;
        $controller->setManifest($manifest);
        $controller->setStore($store);

        return $controller;
    }
}
