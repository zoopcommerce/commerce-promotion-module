<?php

namespace Zoop\Promotion\Test\Helper;

use \DateTime;
use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Helper\PromotionManager;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\UnlimitedPromotion;

class PromotionManagerTest extends AbstractTest
{
    public function testEmptyPromotions()
    {
        $this->clearDatabase();

        $store = self::getStore();
        $order = self::createOrder();

        $promotionManager = new PromotionManager(
            self::getDocumentManager(),
            $store
        );

        $promotions = $promotionManager->get($order);

        $this->assertEmpty($promotions);
    }

    public function testInactivePromotions()
    {
        $this->clearDatabase();

        $store = self::getStore();
        $order = self::createOrder();

        self::createLimitedPromotion(1, 1, 0, 0, null, null, null, false);
        self::createUnlimitedPromotion(2,  null, null, null, false);

        $promotionManager = new PromotionManager(
            self::getDocumentManager(),
            $store
        );

        $promotions = $promotionManager->get($order);

        $this->assertCount(0, $promotions);
    }

    public function testSimpleLimitedPromotions()
    {
        $this->clearDatabase();

        $store = self::getStore();
        $order = self::createOrder();

        self::createLimitedPromotion(1, 1);
        self::createLimitedPromotion(2, 1, 1);

        $promotionManager = new PromotionManager(
            self::getDocumentManager(),
            $store
        );

        $promotions = $promotionManager->get($order);

        $this->assertCount(2, $promotions);

        $promotion1 = current($promotions);
        $promotion2 = end($promotions);

        $this->assertEquals(1, $promotion1->getLimit());
        $this->assertEquals(1, $promotion1->getNumberAvailable());

        $this->assertEquals(2, $promotion2->getLimit());
        $this->assertEquals(1, $promotion2->getNumberAvailable());
        $this->assertEquals(1, $promotion2->getNumberInCart());
    }

    public function testSimpleUnlimitedPromotions()
    {
        $this->clearDatabase();

        $store = self::getStore();
        $order = self::createOrder();

        self::createUnlimitedPromotion(1);
        self::createUnlimitedPromotion(2);

        $promotionManager = new PromotionManager(
            self::getDocumentManager(),
            $store
        );

        $promotions = $promotionManager->get($order);

        $this->assertCount(2, $promotions);

        $promotion1 = current($promotions);
        $promotion2 = end($promotions);

        $this->assertEquals(1, $promotion1->getNumberUsed());
        $this->assertEquals(2, $promotion2->getNumberUsed());
    }

    public function testSimpleMixedPromotions()
    {
        $this->clearDatabase();

        $store = self::getStore();
        $order = self::createOrder();

        self::createLimitedPromotion(1, 1);
        self::createUnlimitedPromotion(2);

        $promotionManager = new PromotionManager(
            self::getDocumentManager(),
            $store
        );

        $promotions = $promotionManager->get($order);

        $this->assertCount(2, $promotions);

        $promotion1 = current($promotions);
        $promotion2 = end($promotions);

        $this->assertTrue($promotion1 instanceof LimitedPromotion);
        $this->assertEquals(1, $promotion1->getLimit());
        $this->assertEquals(1, $promotion1->getNumberAvailable());
        $this->assertEquals(0, $promotion1->getNumberUsed());

        $this->assertTrue($promotion2 instanceof UnlimitedPromotion);
        $this->assertEquals(2, $promotion2->getNumberUsed());
    }

    public function testDateFilteredPromotions()
    {
        $this->clearDatabase();

        $store = self::getStore();
        $order = self::createOrder();


        $dateStart1 = new DateTime('+1 Month');
        $dateEnd1 = new DateTime('+2 Months');
        self::createLimitedPromotion(1, 1, 0, 0, $dateStart1, $dateEnd1);
        self::createUnlimitedPromotion(2, $dateStart1, $dateEnd1);

        $dateStart2 = new DateTime('-1 Month');
        $dateEnd2 = new DateTime('+1 Month');
        self::createLimitedPromotion(1, 1, 0, 0, $dateStart2, $dateEnd2);
        self::createUnlimitedPromotion(0, $dateStart2, $dateEnd2);

        $promotionManager = new PromotionManager(
            self::getDocumentManager(),
            $store
        );

        $promotions = $promotionManager->get($order);

        $this->assertCount(2, $promotions);

        $promotion1 = current($promotions);
        $promotion2 = end($promotions);

        $this->assertTrue($promotion1 instanceof LimitedPromotion);
        $this->assertEquals(1, $promotion1->getLimit());
        $this->assertEquals(1, $promotion1->getNumberAvailable());
        $this->assertEquals(0, $promotion1->getNumberUsed());

        $this->assertTrue($promotion2 instanceof UnlimitedPromotion);
        $this->assertEquals(0, $promotion2->getNumberUsed());
    }

    public function testCouponPromotions()
    {
        $this->clearDatabase();

        $store = self::getStore();

        $presentCouponCode = 'present-coupon';
        $missingCouponCode = 'missing-coupon';

        $order = self::createOrder($presentCouponCode);

        self::createLimitedPromotion(1, 1, 0, 0, null, null, [$presentCouponCode]);
        self::createUnlimitedPromotion(0, null, null, [$presentCouponCode]);

        self::createLimitedPromotion(1, 1, 0, 0, null, null, [$missingCouponCode]);
        self::createUnlimitedPromotion(0, null, null, [$missingCouponCode]);

        $promotionManager = new PromotionManager(
            self::getDocumentManager(),
            $store
        );

        $promotions = $promotionManager->get($order);

        $this->assertCount(2, $promotions);

        $promotion1 = current($promotions);
        $promotion2 = end($promotions);

        $this->assertTrue($promotion1 instanceof LimitedPromotion);
        $this->assertEquals(1, $promotion1->getLimit());
        $this->assertEquals(1, $promotion1->getNumberAvailable());
        $this->assertEquals(0, $promotion1->getNumberUsed());
        $this->assertNotEmpty($promotion1->getCouponsMap());

        $this->assertTrue($promotion2 instanceof UnlimitedPromotion);
        $this->assertEquals(0, $promotion2->getNumberUsed());
        $this->assertNotEmpty($promotion2->getCouponsMap());

        //remove coupon from order and clear promotion
        $promotionManager->clear();
        $order->setCoupon(null);

        $promotions = $promotionManager->get($order);
        $this->assertCount(0, $promotions);
    }
}
