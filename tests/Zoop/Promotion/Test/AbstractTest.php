<?php

namespace Zoop\Promotion\Test;

use \DateTime;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zoop\Shard\Manifest;
use Zoop\Shard\Serializer\Unserializer;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Order\DataModel\Order;
use Zoop\Order\DataModel\Total;
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

    protected static function createOrder($coupon = null)
    {
        $order = TestData::createOrder(self::getUnserializer());
        
        $order->setCoupon($coupon);
        
        self::getDocumentManager()->persist($order);
        self::getDocumentManager()->flush($order);
        
        return $order;
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
//
//    protected function createLimitedPromotion($limits = [], $startDate = null, $endDate = null, $couponCode = null)
//    {
//        $limited = new LimitedPromotion;
//        $limited->addStore($this->getStore());
//
//        if (!empty($startDate)) {
//            $limited->setStartDate(new DateTime($startDate));
//        }
//        if (!empty($endDate)) {
//            $limited->setEndDate(new DateTime($endDate));
//        }
//        $limited->setLimit((int) $limits['limit']);
//        $limited->setNumberAvailable((int) $limits['available']);
//        $limited->setNumberInCart((int) (isset($limits['in-cart']) ? $limits['in-cart'] : 0));
//        $limited->setNumberUsed((int) (isset($limits['used']) ? $limits['used'] : 0));
//
//        $this->addCouponToCouponMap($limited, $couponCode);
//
//        $this->getDocumentManager()->persist($limited);
//        $this->getDocumentManager()->flush($limited);
//
//        $id = $limited->getId();
//        $this->getDocumentManager()->clear($limited);
//        unset($limited);
//
//        $limited = $this->getPromotion($id);
//
//        for ($i = 0; $i < (int) $limits['limit']; $i++) {
//            if (!empty($couponCode) && is_array($couponCode)) {
//                foreach ($couponCode as $code) {
//                    $registry = new Finite;
//                    $registry->setPromotion($limited);
//                    $coupon = new Coupon;
//                    $coupon->setCode($code);
//                    $registry->setCoupon($coupon);
//                    $this->getDocumentManager()->persist($registry);
//                }
//            } else {
//                $registry = new Finite;
//                $registry->setPromotion($limited);
//                if (!empty($couponCode)) {
//                    $coupon = new Coupon;
//                    $coupon->setCode($couponCode);
//                    $registry->setCoupon($coupon);
//                }
//                $this->getDocumentManager()->persist($registry);
//                $this->getDocumentManager()->flush($registry);
//                $this->getDocumentManager()->clear($registry);
//            }
//        }
//
//        // this is crazy. Because adding references to other documents
//        // triggers an "update" we still get errors on createdOn etc.
//        $this->getDocumentManager()->clear($limited);
//        unset($limited);
//        $limited = $this->getPromotion($id);
//
//        return $limited;
//    }
//
//    protected function createUnlimitedPromotion($startDate = null, $endDate = null, $couponCode = null, $used = 0)
//    {
//        $unlimited = new UnlimitedPromotion;
//        $unlimited->addStore($this->getStore());
//        $unlimited->setNumberUsed((int) $used);
//
//        if (!empty($startDate)) {
//            $unlimited->setStartDate(new DateTime($startDate));
//        }
//        if (!empty($endDate)) {
//            $unlimited->setEndDate(new DateTime($endDate));
//        }
//
//        $this->addCouponToCouponMap($unlimited, $couponCode);
//
//        $this->getDocumentManager()->persist($unlimited);
//        $this->getDocumentManager()->flush($unlimited);
//        $id = $unlimited->getId();
//        $this->getDocumentManager()->clear($unlimited);
//        unset($unlimited);
//
//        $unlimited = $this->getPromotion($id);
//
//        $registry = new Infinite;
//        $registry->setPromotion($unlimited);
//        if (!empty($couponCode) && is_array($couponCode)) {
//            foreach ($couponCode as $code) {
//                $coupon = new Coupon;
//                $coupon->setCode($code);
//                $registry->addCoupon($coupon);
//            }
//        } else {
//            if (!empty($couponCode)) {
//                $coupon = new Coupon;
//                $coupon->setCode($couponCode);
//                $registry->addCoupon($coupon);
//            }
//        }
//        $this->getDocumentManager()->persist($registry);
//        $this->getDocumentManager()->flush($registry);
//
//        // this is crazy. Because adding references to other documents
//        // triggers an "update" we still get errors on createdOn etc.
//        $this->getDocumentManager()->clear($unlimited);
//        unset($unlimited);
//        $unlimited = $this->getPromotion($id);
//
//        return $unlimited;
//    }

    /**
     *
     * @param string $id
     * @return Zoop\Promotion\DataModel\AbstractPromotion
     */
    protected function getPromotion($id)
    {
        $promotion = $this->getDocumentManager()
            ->createQueryBuilder(self::DOCUMENT_ABSTRACT_PROMOTION)
            ->field('id')->equals($id)
            ->getQuery()
            ->getSingleResult();

        return $promotion;
    }

    protected function addCouponToCouponMap(PromotionInterface $promotion, $couponCode = null)
    {
        if (!empty($couponCode)) {
            if (is_array($couponCode)) {
                foreach ($couponCode as $code) {
                    $promotion->addCouponToMap($code);
                }
            } else {
                $promotion->addCouponToMap($couponCode);
            }
        }
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
