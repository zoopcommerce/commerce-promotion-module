<?php

namespace Zoop\Promotion\Service;

use Zoop\Promotion\Controller\PromotionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PromotionControllerFactory implements FactoryInterface
{
    /**
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return PromotionController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $store = $serviceLocator->get('zoop.commerce.store.legacy.active');
        $manifest = $serviceLocator->get('shard.commerce.manifest');

        $controller = new PromotionController;
        $controller->setManifest($manifest);
        $controller->setStore($store);

        return $controller;
    }
}
