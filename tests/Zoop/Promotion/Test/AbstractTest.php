<?php

namespace Zoop\Promotion\Test;

use \DateTime;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zoop\Shard\Manifest;
use Zoop\Shard\Serializer\Unserializer;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Order\DataModel\OrderInterface;
use Zoop\Store\DataModel\Store;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Promotion\DataModel\Register\Infinite;
use Zoop\Promotion\DataModel\Register\Finite;
use Zoop\Promotion\DataModel\Register\Coupon;
use Zoop\Promotion\Test\Assets\TestData;
use Zoop\Shard\Core\Events;

abstract class AbstractTest extends AbstractHttpControllerTestCase
{
    const DOCUMENT_ORDER = 'Zoop\Order\DataModel\Order';
    const DOCUMENT_STORE = 'Zoop\Store\DataModel\Store';
    const DOCUMENT_ABSTRACT_PROMOTION = 'Zoop\Promotion\DataModel\AbstractPromotion';
    const DOCUMENT_UNLIMITED_PROMOTION = 'Zoop\Promotion\DataModel\UnlimitedPromotion';
    const DOCUMENT_LIMITED_PROMOTION = 'Zoop\Promotion\DataModel\LimitedPromotion';

    protected static $documentManager;
    protected static $dbName;
    protected static $unserializer;
    protected static $manifest;
    protected static $store;

    public function setUp()
    {
        if (!isset(self::$documentManager)) {
            $this->setApplicationConfig(
                require __DIR__ . '/../../../test.application.config.php'
            );
            self::$documentManager = $this->getApplicationServiceLocator()
                ->get('shard.commerce.modelmanager');

            $eventManager = self::$documentManager->getEventManager();
            $eventManager->addEventListener(Events::EXCEPTION, $this);
        }

        if (!isset(self::$dbName)) {
            self::$dbName = $this->getApplicationServiceLocator()
                ->get('config')['doctrine']['odm']['connection']['commerce']['dbname'];
        }

        if (!isset(self::$manifest)) {
            self::$manifest = $this->getApplicationServiceLocator()
                ->get('shard.commerce.manifest');
        }

        if (!isset(self::$unserializer)) {
            self::$unserializer = self::$manifest->getServiceManager()
                ->get('unserializer');
        }

        $this->calls = [];
    }

    /**
     * @return DocumentManager
     */
    public static function getDocumentManager()
    {
        return self::$documentManager;
    }

    /**
     *
     * @return string
     */
    public static function getDbName()
    {
        return self::$dbName;
    }

    /**
     *
     * @return Manifest
     */
    public static function getManifest()
    {
        return self::$manifest;
    }

    /**
     *
     * @return Unserializer
     */
    public static function getUnserializer()
    {
        return self::$unserializer;
    }

    /**
     * @param string $coupon
     * @return OrderInterface
     */
    protected static function createOrder($coupon = null)
    {
        $order = TestData::createOrder(self::getUnserializer());

        $order->setCoupon($coupon);

        self::getDocumentManager()->persist($order);
        self::getDocumentManager()->flush($order);

        return $order;
    }

    /**
     *
     * @param int $limit
     * @param int $available
     * @param int $inCart
     * @param int $used
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param array $couponCodes
     * @param boolean $active
     *
     * @return LimitedPromotion
     */
    protected static function createLimitedPromotion(
        $limit = 0,
        $available = 0,
        $inCart = 0,
        $used = 0,
        DateTime $startDate = null,
        DateTime $endDate = null,
        $couponCodes = [],
        $active = true
    )
    {
        $promotion = TestData::createLimitedPromotion(self::getUnserializer());
        $promotion->setLimit($limit);
        $promotion->setNumberAvailable($available);
        $promotion->setNumberInCart($inCart);
        $promotion->setNumberUsed($used);
        $promotion->setActive($active);

        if(!empty($startDate)) {
            $promotion->setStartDate($startDate);
        }
        if(!empty($endDate)) {
            $promotion->setEndDate($endDate);
        }

        if(!empty($couponCodes)) {
            foreach($couponCodes as $couponCode) {
                $promotion->addCouponToMap($couponCode);
            }
        }

        self::getDocumentManager()->persist($promotion);
        self::getDocumentManager()->flush($promotion);

        //create registry
        for ($i = 0; $i < (int) $limit; $i++) {
            if (!empty($couponCodes) && is_array($couponCodes)) {
                foreach ($couponCodes as $couponCode) {
                    $register = TestData::createFiniteRegister(self::getUnserializer());
                    $register->setPromotion($promotion);

                    $coupon = new Coupon;
                    $coupon->setCode($couponCode);

                    $register->setCoupon($coupon);

                    self::getDocumentManager()->persist($register);
                    self::getDocumentManager()->flush($register);
                    self::getDocumentManager()->clear($register);
                }
            } else {
                $register = TestData::createFiniteRegister(self::getUnserializer());
                $register->setPromotion($promotion);
                self::getDocumentManager()->persist($register);
                self::getDocumentManager()->flush($register);
                self::getDocumentManager()->clear($register);
            }
        }

        self::getDocumentManager()->clear($promotion);

        return $promotion;
    }

    /**
     *
     * @param int $used
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $couponCodes
     * @param boolean $active
     *
     * @return UnlimitedPromotion
     */
    protected static function createUnlimitedPromotion(
        $used = 0,
        DateTime $startDate = null,
        DateTime $endDate = null,
        $couponCodes = [],
        $active = true
    )
    {
        $promotion = TestData::createUnlimitedPromotion(self::getUnserializer());
        $promotion->setNumberUsed($used);
        $promotion->setActive($active);

        if(!empty($startDate)) {
            $promotion->setStartDate($startDate);
        }
        if(!empty($endDate)) {
            $promotion->setEndDate($endDate);
        }

        if(!empty($couponCodes)) {
            foreach($couponCodes as $couponCode) {
                $promotion->addCouponToMap($couponCode);
            }
        }

        self::getDocumentManager()->persist($promotion);
        self::getDocumentManager()->flush($promotion);

        //create registry
        $registry = TestData::createInfiniteRegister(self::getUnserializer());
        $registry->setPromotion($promotion);
        if (!empty($couponCode) && is_array($couponCode)) {
            foreach ($couponCode as $code) {
                $coupon = new Coupon;
                $coupon->setCode($code);
                $registry->addCoupon($coupon);
            }
        }

        self::getDocumentManager()->persist($registry);
        self::getDocumentManager()->flush($registry);
        self::getDocumentManager()->clear($registry);

        self::getDocumentManager()->clear($promotion);

        return $promotion;
    }

    /**
     * @return Store
     */
    protected static function getStore()
    {
        if (!isset(self::$store)) {
            $store = TestData::createStore(self::getUnserializer());

            self::getDocumentManager()->persist($store);
            self::getDocumentManager()->flush($store);
            self::$store = $store;
        }
        return self::$store;
    }

    /**
     *
     * @param string $id
     * @return Zoop\Promotion\DataModel\AbstractPromotion
     */
    protected static function getPromotion($id)
    {
        $promotion = self::getDocumentManager()
            ->createQueryBuilder(self::DOCUMENT_ABSTRACT_PROMOTION)
            ->field('id')->equals($id)
            ->getQuery()
            ->getSingleResult();

        return $promotion;
    }

    public static function tearDownAfterClass()
    {
        self::clearDatabase();
    }

    public static function clearDatabase()
    {
        if (isset(self::$documentManager) && isset(self::$dbName)) {
            $collections = self::$documentManager
                ->getConnection()
                ->selectDatabase(self::$dbName)->listCollections();

            foreach ($collections as $collection) {
                /* @var $collection \MongoCollection */
                $collection->drop();
            }
        }
    }

    public function __call($name, $arguments)
    {
        die(var_dump($name, $arguments[0]));
        $this->calls[$name] = $arguments;
    }
}
