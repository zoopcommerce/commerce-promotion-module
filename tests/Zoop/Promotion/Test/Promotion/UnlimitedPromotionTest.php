<?php

namespace Zoop\Promotion\Test\Promotion;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Promotion;

class UnlimitedPromotionTest extends AbstractTest
{
    public function testSuccessfullyReservedPromotion()
    {
        $order = $this->createOrder(1, 100);

        $promotion = new Promotion;
        $promotion->setDocumentManager($this->getDocumentManager());
        $promotion->setOrder($order);

        $unlimitedPromotion = $this->createUnlimitedPromotion();

        $reserved = $promotion->reservePromotion($unlimitedPromotion);

        $this->assertEquals(true, $reserved);
    }

    public function testSuccessfullySetUsedPromotion()
    {
        $order = $this->createOrder(1, 100);

        $promotion = new Promotion;
        $promotion->setDocumentManager($this->getDocumentManager());
        $promotion->setOrder($order);

        $unlimitedPromotion = $this->createUnlimitedPromotion();

        $reserved = $promotion->reservePromotion($unlimitedPromotion);
        $promotion->setUsed($unlimitedPromotion);

        $this->assertEquals(true, $reserved);
        $this->assertEquals(1, $unlimitedPromotion->getNumberUsed());
    }
}
