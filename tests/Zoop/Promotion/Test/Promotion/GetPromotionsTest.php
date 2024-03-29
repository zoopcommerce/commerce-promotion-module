<?php

namespace Zoop\Promotion\Test\Promotion;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\PromotionChain;
use Zoop\Order\DataModel\Order;

class GetPromotionsTest extends AbstractTest
{
    public function testGetPromotionWithoutExpiry()
    {
        $this->clearDatabase();

        $order = $this->createOrder(1, 100);

        //add promotion to DB
        $this->createLimitedPromotion(['limit' => 1, 'available' => 1, 'in-cart' => 0, 'used' => 0]);
        $this->getDocumentManager()->clear();
        $this->createLimitedPromotion(['limit' => 1, 'available' => 1, 'in-cart' => 0, 'used' => 0]);
        $this->getDocumentManager()->clear();

        $promotion = $this->getPromotionChain($order);
        $promotion->setPromotions();

        $promotions = $promotion->getPromotions();

        $this->assertCount(2, $promotions);
    }

    public function testGetPromotionWithExpiry()
    {
        $this->clearDatabase();

        $order = $this->createOrder(1, 100);

        //add promotion to DB
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('Yesterday')),
            date('Y-m-d', strtotime('Tomorrow'))
        );
        $this->getDocumentManager()->clear();

        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('Yesterday')),
            date('Y-m-d', strtotime('Tomorrow'))
        );
        $this->getDocumentManager()->clear();

        $promotion = $this->getPromotionChain($order);
        $promotion->setPromotions();

        $promotions = $promotion->getPromotions();

        $this->assertCount(2, $promotions);
    }

    public function testDenyPromotionWithFutureExpiry()
    {
        $this->clearDatabase();

        $order = $this->createOrder(1, 100);

        //add promotion to DB
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('Tomorrow')),
            date('Y-m-d', strtotime('+2 Days'))
        );
        $this->getDocumentManager()->clear();

        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('Tomorrow')),
            date('Y-m-d', strtotime('+2 Days'))
        );
        $this->getDocumentManager()->clear();

        $promotion = $this->getPromotionChain($order);
        $promotion->setPromotions();

        $promotions = $promotion->getPromotions();

        $this->assertCount(0, $promotions);
    }

    public function testDenyPromotionWithPastExpiry()
    {
        $this->clearDatabase();

        $order = $this->createOrder(1, 100);

        //add promotion to DB
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('-2 Days')),
            date('Y-m-d', strtotime('Yesterday'))
        );
        $this->getDocumentManager()->clear();

        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('-2 Days')),
            date('Y-m-d', strtotime('Yesterday'))
        );
        $this->getDocumentManager()->clear();

        $promotion = $this->getPromotionChain($order);
        $promotion->setPromotions();

        $promotions = $promotion->getPromotions();

        $this->assertCount(0, $promotions);
    }

    public function testMixedPromotionWithPastFutureExpiry()
    {
        $this->clearDatabase();

        $order = $this->createOrder(1, 100);

        //add promotion to DB
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('-2 Days')),
            date('Y-m-d', strtotime('Yesterday'))
        );
        $this->getDocumentManager()->clear();

        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('Tomorrow')),
            date('Y-m-d', strtotime('+2 Days'))
        );
        $this->getDocumentManager()->clear();

        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('Yesterday')),
            date('Y-m-d', strtotime('Tomorrow'))
        );
        $this->getDocumentManager()->clear();

        $promotion = $this->getPromotionChain($order);
        $promotion->setPromotions();

        $promotions = $promotion->getPromotions();

        $this->assertCount(1, $promotions);
    }

    public function testGetPromotionWithCoupon()
    {
        $this->clearDatabase();

        $couponCode = 'TEST';
        $order = $this->createOrder(1, 100, $couponCode);

        //add promotion to DB
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            null,
            null,
            $couponCode
        );
        $this->getDocumentManager()->clear();

        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            null,
            null,
            $couponCode
        );
        $this->getDocumentManager()->clear();

        $promotion = $this->getPromotionChain($order);
        $promotion->setPromotions();

        $promotions = $promotion->getPromotions();

        $this->assertCount(2, $promotions);
    }

    public function testMixedPromotionWithCouponExpiry()
    {
        $this->clearDatabase();

        $couponCode = 'TEST';
        $order = $this->createOrder(1, 100, $couponCode);

        //add promotion to DB
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('Yesterday')),
            date('Y-m-d', strtotime('Tomorrow')),
            $couponCode
        );

        $this->getDocumentManager()->clear();

        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            date('Y-m-d', strtotime('-2 Days')),
            date('Y-m-d', strtotime('Yesterday')),
            $couponCode
        );
        $this->getDocumentManager()->clear();

        $promotion = $this->getPromotionChain($order);
        $promotion->setPromotions();

        $promotions = $promotion->getPromotions();

        $this->assertCount(1, $promotions);
    }

    public function testDenyPromotionWithCoupon()
    {
        $this->clearDatabase();

        $couponCode = 'RIGHT';
        $orderCouponCode = 'WRONG';
        $order = $this->createOrder(1, 100, $orderCouponCode);

        //add promotion to DB
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            null,
            null,
            $couponCode
        );
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            null,
            null,
            $couponCode
        );
        $this->getDocumentManager()->clear();

        $promotion = $this->getPromotionChain($order);
        $promotion->setPromotions();

        $promotions = $promotion->getPromotions();

        $this->assertCount(0, $promotions);
    }

    public function testLimitedPromotionWithMultipleCoupons()
    {
        $this->clearDatabase();

        $couponCode1 = 'RIGHT-1';
        $couponCode2 = 'RIGHT-2';
        $order1 = $this->createOrder(1, 100, $couponCode1);
        $order2 = $this->createOrder(2, 100, $couponCode2);
        $order3 = $this->createOrder(3, 100);

        //add promotion to DB
        $this->createLimitedPromotion(
            ['limit' => 1, 'available' => 1],
            null,
            null,
            [
                $couponCode1,
                $couponCode2
            ]
        );
        $this->getDocumentManager()->clear();

        $promotion1 = $this->getPromotionChain($order1);
        $promotion1->setPromotions();

        $promotions = $promotion1->getPromotions();
        $this->assertCount(1, $promotions);

        $promotion2 = $this->getPromotionChain($order2);
        $promotion2->setPromotions();

        $promotions = $promotion2->getPromotions();
        $this->assertCount(1, $promotions);

        $promotion3 = $this->getPromotionChain($order3);
        $promotion3->setPromotions();

        $promotions = $promotion3->getPromotions();
        $this->assertCount(0, $promotions);
    }

    public function testUnlimitedPromotionWithMultipleCoupons()
    {
        $this->clearDatabase();

        $couponCode1 = 'RIGHT-1';
        $couponCode2 = 'RIGHT-2';
        $order1 = $this->createOrder(1, 100, $couponCode1);
        $order2 = $this->createOrder(2, 100, $couponCode2);
        $order3 = $this->createOrder(3, 100);

        //add promotion to DB
        $this->createUnlimitedPromotion(null, null, [
            $couponCode1,
            $couponCode2
        ]);
        $promotion1 = $this->getPromotionChain($order1);
        $promotion1->setPromotions();

        $promotions = $promotion1->getPromotions();
        $this->assertCount(1, $promotions);

        $promotion2 = $this->getPromotionChain($order2);
        $promotion2->setPromotions();

        $promotions = $promotion2->getPromotions();
        $this->assertCount(1, $promotions);

        $promotion3 = $this->getPromotionChain($order3);
        $promotion3->setPromotions();

        $promotions = $promotion3->getPromotions();
        $this->assertCount(0, $promotions);
    }

    protected function getPromotionChain(Order $order)
    {
        $dm = $this->getDocumentManager();
        $manifest = $this->getManifest();
        $unserializer = $manifest->getServiceManager()->get('unserializer');
        $serializer = $manifest->getServiceManager()->get('serializer');
        $softDelete = $manifest->getServiceManager()->get('softdeleter');

        $store = $this->getStore();

        $promotionChain = new PromotionChain;

        $promotionChain->setDocumentManager($dm);
        $promotionChain->setSerializer($serializer);
        $promotionChain->setUnserializer($unserializer);
        $promotionChain->setSoftDelete($softDelete);

        $promotionChain->setStore($store);
        $promotionChain->setOrder($order);

        return $promotionChain;
    }
}
