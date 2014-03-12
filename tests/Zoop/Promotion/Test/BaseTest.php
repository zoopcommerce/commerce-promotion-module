<?php

namespace Zoop\Promotion\Test;

use \DateTime;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zoop\Shard\Manifest;
use Zoop\Shard\Serializer\Unserializer;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Legacy\Promotion\DataModel\PromotionInterface;
use Zoop\Legacy\Order\DataModel\Order;
use Zoop\Legacy\Order\DataModel\Total;
use Zoop\Legacy\Store\DataModel\Store;
use Zoop\Legacy\Promotion\DataModel\LimitedPromotion;
use Zoop\Legacy\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Legacy\Promotion\DataModel\Register\Infinite;
use Zoop\Legacy\Promotion\DataModel\Register\Finite;
use Zoop\Legacy\Promotion\DataModel\Register\Coupon;
use Zoop\Shard\Core\Events;

abstract class BaseTest extends AbstractHttpControllerTestCase
{
    const TEST_DB = 'zoop-phpunit';
    const DOCUMENT_ORDER = 'Zoop\Legacy\Order\DataModel\Order';
    const DOCUMENT_STORE = 'Zoop\Legacy\Store\DataModel\Store';
    const DOCUMENT_ABSTRACT_PROMOTION = 'Zoop\Legacy\Promotion\DataModel\AbstractPromotion';
    const DOCUMENT_UNLIMITED_PROMOTION = 'Zoop\Legacy\Promotion\DataModel\UnlimitedPromotion';
    const DOCUMENT_LIMITED_PROMOTION = 'Zoop\Legacy\Promotion\DataModel\LimitedPromotion';

    protected $documentManager;
    protected $unserializer;
    protected $manifest;
    protected $db;
    protected $store;

    public function setUp()
    {
        require_once __DIR__ . '/../../../public/commerce/config.php';
        require_once __DIR__ . '/../../../module/Commerce/src/constants.php';

        $this->setApplicationConfig(
                include __DIR__ . '/../../../config/application.config.php'
        );

        $tempDir = __DIR__ . '/../../../data/temp/';

        $manifest = $this->getApplicationServiceLocator()
                ->get('shard.commerce.manifest');

        $dm = $this->getApplicationServiceLocator()
                ->get('shard.commerce.modelmanager');

        $unserializer = $manifest->getServiceManager()
                ->get('unserializer');

        $this->setManifest($manifest);
        $this->setDocumentManager($dm);

        $this->setUnserializer($unserializer);

        $eventManager = $dm->getEventManager();
        $eventManager->addEventListener(Events::EXCEPTION, $this);

        $this->calls = [];
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
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    /**
     *
     * @return Unserializer
     */
    public function getUnserializer()
    {
        return $this->unserializer;
    }

    /**
     *
     * @param DocumentManager $documentManager
     */
    public function setDocumentManager(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     *
     * @param Unserializer $unserializer
     */
    public function setUnserializer(Unserializer $unserializer)
    {
        $this->unserializer = $unserializer;
    }

    protected function getOrder($id, $totalOrderPrice = null, $coupon = null)
    {
        $order = new Order;
        $order->setCreatedOn(new DateTime);
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
        if (!isset($this->store)) {
            $store = new Store;
            $store->setName('Demo');
            $store->setSubdomain('demo');

            $this->getDocumentManager()->persist($store);
            $this->getDocumentManager()->flush($store);
            $id = $store->getId();
            $this->getDocumentManager()->clear($store);
            unset($store);

            $this->store = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_STORE)
                    ->field('id')->equals($id)
                    ->getQuery()
                    ->getSingleResult();
        }
        return $this->store;
    }

    protected function createLimitedPromotion($limits = [], $startDate = null, $endDate = null, $couponCode = null)
    {
        $limited = new LimitedPromotion;
        $limited->addStore($this->getStore()->getSubdomain());
        $limited->setCreatedOn(new DateTime);

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
        $unlimited->setCreatedOn(new DateTime);
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
     * @return Zoop\Legacy\Promotion\DataModel\AbstractPromotion
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

    public function tearDown()
    {
        $this->clearDatabase();
    }

    public function clearDatabase()
    {
        if ($this->documentManager) {
            $db = $this->getApplicationServiceLocator()->get('config')['doctrine']['odm']['connection']['commerce']['dbname'];
            $collections = $this->getDocumentManager()
                            ->getConnection()
                            ->selectDatabase($db)->listCollections();

            foreach ($collections as $collection) {
                /* @var $collection \MongoCollection */
                $collection->drop();
            }
        }
    }

    public function __call($name, $arguments)
    {
        var_dump($name, $arguments[0]);
        $this->calls[$name] = $arguments;
    }

}
