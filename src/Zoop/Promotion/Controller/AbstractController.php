<?php

namespace Zoop\Promotion\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Promotion\Compiler;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Store\Store;
use Zoop\Shard\Manifest;
use Zoop\Shard\SoftDelete\Extension as SoftDelete;
use Zoop\Shard\Serializer\Serializer;
use Zoop\Shard\Serializer\Unserializer;

abstract class AbstractController
{
    const PROMOTION_DATA_MODEL = 'Zoop\Promotion\DataModel\AbstractPromotion';
    const LIMITED_PROMOTION_DATA_MODEL = 'Zoop\Promotion\DataModel\LimitedPromotion';
    const REGISTER_DATA_MODEL = 'Zoop\Promotion\DataModel\Register\AbstractRegister';

    protected $manifest;
    protected $store;

    /**
     *
     * @return DocumentManager
     */
    public function getDm()
    {
        return $this->getManifest()->getServiceManager()->get('modelmanager');
    }

    /**
     *
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->getManifest()->getServiceManager()->get('serializer');
    }

    /**
     *
     * @return Unserializer
     */
    public function getUnserializer()
    {
        return $this->getManifest()->getServiceManager()->get('unserializer');
    }

    /**
     *
     * @return SoftDelete
     */
    public function getSoftDelete()
    {
        return $this->getManifest()->getServiceManager()->get('softDeleter');
    }

    /**
     *
     * @return Manifest
     */
    public function getManifest()
    {
        return $this->manifest;
    }

    /**
     *
     * @param Manifest $manifest
     */
    public function setManifest(Manifest $manifest)
    {
        $this->manifest = $manifest;
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

    /**
     *
     * @return string
     */
    public function getStoreSubDomain()
    {
        return $this->getStore()->getSubDomain();
    }

    protected function save($data)
    {
        if (!empty($data)) {
            if (is_array($data)) {
                foreach ($data as $d) {
                    $this->getDm()->persist($d);
                }
            } else {
                $this->getDm()->persist($data);
            }
            $this->getDm()->flush();
        }
    }

    /**
     *
     * @param PromotionInterface $promotion
     */
    public function addFunctionsToPromotion(PromotionInterface $promotion)
    {
        //compile the promo discounts into a function we can use later
        $compiler = new Compiler();
        $compiler->setPromotion($promotion);
        $compiler->compile(true);

        $promotion->setCartFunction($compiler->getCompiledCartFunction());
        $promotion->setProductFunction($compiler->getCompiledProductFunction());
    }

}
