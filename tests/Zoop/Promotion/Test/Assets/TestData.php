<?php

namespace Zoop\Promotion\Test\Assets;

use Zoop\Shard\Serializer\Unserializer;
use Zoop\Store\DataModel\Store;
use Zoop\Order\DataModel\Order;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Promotion\DataModel\Register\Finite;

class TestData
{
    const DOCUMENT_ORDER = 'Zoop\Order\DataModel\Order';
    const DOCUMENT_STORE = 'Zoop\Store\DataModel\Store';
    const DOCUMENT_UNLIMITED_PROMOTION = 'Zoop\Promotion\DataModel\UnlimitedPromotion';
    const DOCUMENT_LIMITED_PROMOTION = 'Zoop\Promotion\DataModel\LimitedPromotion';
    const DOCUMENT_FINITE_REGISTER = 'Zoop\Promotion\DataModel\Register\Finite';
    const DOCUMENT_INFINITE_REGISTER = 'Zoop\Promotion\DataModel\Register\Infinite';
    
    /**
     * @param Unserializer $unserializer
     * @return LimitedPromotion
     */
    public static function createLimitedPromotion(Unserializer $unserializer)
    {
        $data = self::getJson('Limited/LimitedPromotion');
        
        return $unserializer->fromJson($data, self::DOCUMENT_LIMITED_PROMOTION);
    }
    /**
     * @param Unserializer $unserializer
     * @return Finite
     */
    public static function createFiniteRegister(Unserializer $unserializer)
    {
        $data = self::getJson('Limited/FiniteRegister');
        
        return $unserializer->fromJson($data, self::DOCUMENT_FINITE_REGISTER);
    }
    /**
     * @param Unserializer $unserializer
     * @return UnlimitedPromotion
     */
    public static function createUnlimitedPromotion(Unserializer $unserializer)
    {
        $data = self::getJson('Unlimited/UnlimitedPromotion');
        
        return $unserializer->fromJson($data, self::DOCUMENT_UNLIMITED_PROMOTION);
    }
    /**
     * @param Unserializer $unserializer
     * @return Infinite
     */
    public static function createInfiniteRegister(Unserializer $unserializer)
    {
        $data = self::getJson('Unlimited/InfiniteRegister');
        
        return $unserializer->fromJson($data, self::DOCUMENT_INFINITE_REGISTER);
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
