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
use Zoop\Shard\Core\Events;

abstract class BaseTest extends AbstractHttpControllerTestCase
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
            self::$documentManager = $this->getApplicationServiceLocator()->get('shard.commerce.modelmanager');

            $eventManager = self::$documentManager->getEventManager();
            $eventManager->addEventListener(Events::EXCEPTION, $this);
        }

        if (!isset(self::$dbName)) {
            self::$dbName = $this->getApplicationServiceLocator()->get('config')['doctrine']['odm']['connection']['commerce']['dbname'];
        }

        if (!isset(self::$manifest)) {
            self::$manifest = $this->getApplicationServiceLocator()->get('shard.commerce.manifest');
        }

        if (!isset(self::$unserializer)) {
            self::$unserializer = self::$manifest->getServiceManager()->get('unserializer');
        }

        if (!isset(self::$store)) {
            self::$store = $this->getStore();
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

    protected function getOrder($id, $totalOrderPrice = null, $coupon = null)
    {
        $store= $this->getStore();
        die(vaR_dump($store));
        $order = new Order;
        $order->setLegacyId($id);
        $order->setStore($this->getStore()->getSubdomain());

        if (!empty($coupon)) {
            $order->setCoupon($coupon);
        }

        if (!empty($totalOrderPrice)) {
            $total = new Total;
            $total->setOrderPrice($totalOrderPrice);
            $total->setProductPrice($totalOrderPrice);
            $order->setTotal($total);
        }
        $order->setHasProducts(true);

        $this->getDocumentManager()->persist($order);
        $this->getDocumentManager()->flush($order);
        $id = $order->getId();
        $this->getDocumentManager()->clear($order);
        unset($order);

        $order = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_ORDER)
                ->field('id')->equals($id)
                ->getQuery()
                ->getSingleResult();

        return $order;
    }

    /**
     * @return Store
     */
    protected function getStore()
    {
        if (!isset(self::$store)) {
            $store = new Store;
            $store->setName('Demo');
            $store->setSubdomain('demo');
            $store->setSlug('demo');
            $store->setEmail('info@demo.com');

            $this->getDocumentManager()->persist($store);
            $this->getDocumentManager()->flush($store);
            $id = $store->getId();
            $this->getDocumentManager()->clear($store);
           self::$store = 

            self::$store = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_STORE)
                ->field('id')->equals($id)
                ->getQuery()
                ->getSingleResult();
            die(VaR_dump(self::$store , $id));
        }
        return self::$store;
    }

    protected function createLimitedPromotion($limits = [], $startDate = null, $endDate = null, $couponCode = null)
    {
        $limited = new LimitedPromotion;
        $limited->addStore($this->getStore()->getSubdomain());

        if (!empty($startDate)) {
            $limited->setStartDate(new DateTime($startDate));
        }
        if (!empty($endDate)) {
            $limited->setEndDate(new DateTime($endDate));
        }
        $limited->setLimit((int) $limits['limit']);
        $limited->setNumberAvailable((int) $limits['available']);
        $limited->setNumberInCart((int) $limits['inCart']);
        $limited->setNumberUsed((int) $limits['used']);

        $this->addCouponToCouponMap($limited, $couponCode);

        $this->getDocumentManager()->persist($limited);
        $this->getDocumentManager()->flush($limited);

        $id = $limited->getId();
        $this->getDocumentManager()->clear($limited);
        unset($limited);

        $limited = $this->getPromotion($id);

        for ($i = 0; $i < (int) $limits['limit']; $i++) {
            if (!empty($couponCode) && is_array($couponCode)) {
                foreach ($couponCode as $code) {
                    $registry = new Finite;
                    $registry->setPromotion($limited);
                    $coupon = new Coupon;
                    $coupon->setCode($code);
                    $registry->setCoupon($coupon);
                    $this->getDocumentManager()->persist($registry);
                }
            } else {
                $registry = new Finite;
                $registry->setPromotion($limited);
                if (!empty($couponCode)) {
                    $coupon = new Coupon;
                    $coupon->setCode($couponCode);
                    $registry->setCoupon($coupon);
                }
                $this->getDocumentManager()->persist($registry);
                $this->getDocumentManager()->flush($registry);
                $this->getDocumentManager()->clear($registry);
            }
        }

        // this is crazy. Because adding references to other documents
        // triggers an "update" we still get errors on createdOn etc.
        $this->getDocumentManager()->clear($limited);
        unset($limited);
        $limited = $this->getPromotion($id);

        return $limited;
    }

    protected function createUnlimitedPromotion($startDate = null, $endDate = null, $couponCode = null, $used = 0)
    {
        $unlimited = new UnlimitedPromotion;
        $unlimited->addStore($this->getStore()->getSubdomain());
        $unlimited->setNumberUsed((int) $used);

        if (!empty($startDate)) {
            $unlimited->setStartDate(new DateTime($startDate));
        }
        if (!empty($endDate)) {
            $unlimited->setEndDate(new DateTime($endDate));
        }

        $this->addCouponToCouponMap($unlimited, $couponCode);

        $this->getDocumentManager()->persist($unlimited);
        $this->getDocumentManager()->flush();

        $id = $unlimited->getId();
        $this->getDocumentManager()->clear($unlimited);
        unset($unlimited);

        $unlimited = $this->getPromotion($id);

        $registry = new Infinite;
        $registry->setPromotion($unlimited);
        if (!empty($couponCode) && is_array($couponCode)) {
            foreach ($couponCode as $code) {
                $coupon = new Coupon;
                $coupon->setCode($code);
                $registry->addCoupon($coupon);
            }
        } else {
            if (!empty($couponCode)) {
                $coupon = new Coupon;
                $coupon->setCode($couponCode);
                $registry->addCoupon($coupon);
            }
        }
        $this->getDocumentManager()->persist($registry);
        $unlimited->setRegistry($registry);
        $this->getDocumentManager()->flush();

        return $unlimited;
    }

    /**
     *
     * @param string $id
     * @return Zoop\Promotion\DataModel\AbstractPromotion
     */
    protected function getPromotion($id)
    {
        $promotion = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_ABSTRACT_PROMOTION)
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
