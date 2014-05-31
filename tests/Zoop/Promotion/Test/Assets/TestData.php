<?php

namespace Zoop\Promotion\Test\Assets;

use Zoop\Shard\Serializer\Unserializer;
use Zoop\Store\DataModel\Store;
use Zoop\Order\DataModel\Order;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\UnlimitedPromotion;

class TestData
{
    const DOCUMENT_ORDER = 'Zoop\Order\DataModel\Order';
    const DOCUMENT_STORE = 'Zoop\Store\DataModel\Store';
    const DOCUMENT_ABSTRACT_PROMOTION = 'Zoop\Promotion\DataModel\AbstractPromotion';
    const DOCUMENT_UNLIMITED_PROMOTION = 'Zoop\Promotion\DataModel\UnlimitedPromotion';
    const DOCUMENT_LIMITED_PROMOTION = 'Zoop\Promotion\DataModel\LimitedPromotion';
    
    /**
     * @param Unserializer $unserializer
     * @return LimitedPromotion
     */
    public static function createLimitedPromotionWithNoRegister(Unserializer $unserializer)
    {
        $data = self::getJson('Limited/LimitedPromotion');
        
        return $unserializer->fromJson($data, self::DOCUMENT_LIMITED_PROMOTION);
    }
    
    /**
     * @param Unserializer $unserializer
     * @return Store
     */
    public static function createStore(Unserializer $unserializer)
    {
        $data = self::getJson('Store');
        
        return $unserializer->fromJson($data, self::DOCUMENT_STORE);
    }
    
    /**
     * @param Unserializer $unserializer
     * @return Order
     */
    public static function createOrder(Unserializer $unserializer)
    {
        $data = self::getJson('Order');
        
        return $unserializer->fromJson($data, self::DOCUMENT_ORDER);
    }

    protected static function getJson($fileName)
    {
        return file_get_contents(__DIR__ . '/' . $fileName . '.json');
    }
}
