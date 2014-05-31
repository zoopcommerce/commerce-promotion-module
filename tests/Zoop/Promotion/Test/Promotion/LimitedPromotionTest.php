<?php

namespace Zoop\Promotion\Test\Promotion;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Promotion;
use Zoop\Promotion\DataModel\Register\Finite;

class LimitedPromotionTest extends AbstractTest
{
    public function testSuccessfullyReservedPromotion()
    {
        $this->clearDatabase();

        $order = $this->createOrder(1, 100);

        $promotion = new Promotion;
        $promotion->setDocumentManager($this->getDocumentManager());
        $promotion->setOrder($order);

        $limitedPromotion = $this->createLimitedPromotion(['limit' => 2, 'available' => 2]);

        $reserved = $promotion->reservePromotion($limitedPromotion);

        $this->assertEquals(true, $reserved);
        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
        $this->assertEquals(1, $limitedPromotion->getNumberAvailable());
    }

//    public function testSuccessfullyReservedCouponPromotion()
//    {
//        $this->clearDatabase();
//
//        $couponCode = 'TEST';
//
//        $order = $this->createOrder(1, 100, $couponCode);
//
//        $promotion = new Promotion;
//        $promotion->setDocumentManager($this->getDocumentManager());
//        $promotion->setOrder($order);
//
//        $limitedPromotion = $this->createLimitedPromotion(['limit' => 1, 'available' => 1], null, null, $couponCode);
//
//        $reserved = $promotion->reservePromotion($limitedPromotion);
//
//        $this->assertEquals(true, $reserved);
//        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//
//        /* @var $registry Finite */
//        $id = $limitedPromotion->getId();
//        $this->getDocumentManager()->clear();
//
//        $newPromotion = $this->getPromotion($id);
//        $registry = $newPromotion->getRegistry()[0];
//
//        $this->assertInstanceOf('Zoop\Promotion\DataModel\Register\Finite', $registry);
//        $this->assertEquals($couponCode, $registry->getCoupon()->getCode());
//    }
//
//    public function testSubsequentSuccessfullyReservedPromotion()
//    {
//        $this->clearDatabase();
//
//        $order = $this->createOrder(1, 100);
//
//        $promotion = new Promotion;
//        $promotion->setDocumentManager($this->getDocumentManager());
//        $promotion->setOrder($order);
//
//        $limitedPromotion = $this->createLimitedPromotion(['limit' => 1, 'available' => 1]);
//
//        $reserved = $promotion->reservePromotion($limitedPromotion);
//
//        $this->assertEquals(true, $reserved);
//        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//
//        $reserved = $promotion->reservePromotion($limitedPromotion);
//
//        $this->assertEquals(true, $reserved);
//        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//    }
//
//    public function testSuccessfullyRejectedPromotion()
//    {
//        $this->clearDatabase();
//
//        $order1 = $this->createOrder(1, 100);
//
//        $promotion1 = new Promotion;
//        $promotion1->setDocumentManager($this->getDocumentManager());
//        $promotion1->setOrder($order1);
//
//        $order2 = $this->createOrder(2, 100);
//
//        $promotion2 = new Promotion;
//        $promotion2->setDocumentManager($this->getDocumentManager());
//        $promotion2->setOrder($order2);
//
//        //reserve a promotion for order 1
//        $limitedPromotion = $this->createLimitedPromotion(['limit' => 1, 'available' => 1]);
//        $reserved = $promotion1->reservePromotion($limitedPromotion);
//
//        $this->assertEquals(true, $reserved);
//        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//
//        //try to reserve a promotion for order 2, but fail
//        $reserved = $promotion2->reservePromotion($limitedPromotion);
//
//        $this->assertEquals(false, $reserved);
//        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//    }
//
//    public function testSuccessfullyUsedPromotion()
//    {
//        $this->clearDatabase();
//
//        $order = $this->createOrder(1, 100);
//
//        $promotion = new Promotion;
//        $promotion->setDocumentManager($this->getDocumentManager());
//        $promotion->setOrder($order);
//
//        //reserve a promotion for order
//        $limitedPromotion = $this->createLimitedPromotion(['limit' => 1, 'available' => 1]);
//        $reserved = $promotion->reservePromotion($limitedPromotion);
//
//        $this->assertEquals(true, $reserved);
//        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//
//        $used = $promotion->setUsed($limitedPromotion);
//
//        $this->assertEquals(true, $used);
//        $this->assertEquals(0, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//        $this->assertEquals(1, $limitedPromotion->getNumberUsed());
//    }
//
//    public function testSuccessfullyRejectUsedPromotion()
//    {
//        $this->clearDatabase();
//
//        $order1 = $this->createOrder(1, 100);
//
//        $order2 = $this->createOrder(2, 100);
//
//        $promotion1 = new Promotion;
//        $promotion1->setDocumentManager($this->getDocumentManager());
//        $promotion1->setOrder($order1);
//
//        $promotion2 = new Promotion;
//        $promotion2->setDocumentManager($this->getDocumentManager());
//        $promotion2->setOrder($order2);
//
//        //reserve a promotion for order
//        $limitedPromotion = $this->createLimitedPromotion(['limit' => 1, 'available' => 1]);
//        $reserved = $promotion1->reservePromotion($limitedPromotion);
//
//        $this->assertEquals(true, $reserved);
//        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//
//        $used = $promotion1->setUsed($limitedPromotion);
//
//        $this->assertEquals(true, $used);
//        $this->assertEquals(0, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(0, $limitedPromotion->getNumberAvailable());
//        $this->assertEquals(1, $limitedPromotion->getNumberUsed());
//
//        $reserved = $promotion2->reservePromotion($limitedPromotion);
//        $this->assertEquals(false, $reserved);
//    }
//
//    public function testGarbageCollectionNoneUpdated()
//    {
//        $this->clearDatabase();
//
//        $order1 = $this->createOrder(1, 100);
//
//        $promotion1 = new Promotion;
//        $promotion1->setDocumentManager($this->getDocumentManager());
//        $promotion1->setOrder($order1);
//
//        //reserve a promotion for order
//        $limitedPromotion = $this->createLimitedPromotion(['limit' => 2, 'available' => 2]);
//        $reserved = $promotion1->reservePromotion($limitedPromotion);
//
//        $this->assertEquals(true, $reserved);
//        $this->assertEquals(1, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(1, $limitedPromotion->getNumberAvailable());
//
//        $used = $promotion1->setUsed($limitedPromotion);
//
//        $this->assertEquals(true, $used);
//        $this->assertEquals(0, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(1, $limitedPromotion->getNumberAvailable());
//        $this->assertEquals(1, $limitedPromotion->getNumberUsed());
//
//        $promotion1->garbageCollection();
//
//        $this->assertEquals(0, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(1, $limitedPromotion->getNumberAvailable());
//        $this->assertEquals(1, $limitedPromotion->getNumberUsed());
//    }
//
//    public function testGarbageCollectionOneUpdated()
//    {
//        $this->clearDatabase();
//
//        $order1 = $this->createOrder(1, 100);
//
//        $order2 = $this->createOrder(1, 100);
//
//        $promotion1 = new Promotion;
//        $promotion1->setDocumentManager($this->getDocumentManager());
//        $promotion1->setOrder($order1);
//
//        $promotion2 = new Promotion;
//        $promotion2->setDocumentManager($this->getDocumentManager());
//        $promotion2->setOrder($order2);
//
//        //reserve a promotion for order
//        $limitedPromotion = $this->createLimitedPromotion(['limit' => 2, 'available' => 2]);
//        //reserve order 1
//        $promotion1->reservePromotion($limitedPromotion);
//        //set used order 1
//        $promotion1->setUsed($limitedPromotion);
//        //reserve order 2 an hour ago
//        $promotion2->reservePromotion($limitedPromotion, '-1 hour');
//
//        $promotion2->garbageCollection();
//
//        $this->assertEquals(0, $limitedPromotion->getNumberInCart());
//        $this->assertEquals(1, $limitedPromotion->getNumberAvailable());
//        $this->assertEquals(1, $limitedPromotion->getNumberUsed());
//    }
}
