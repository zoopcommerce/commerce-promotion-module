<?php

namespace Zoop\Promotion\Test\Helper;

use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Helper\PromotionManager;

class PromotionManagerTest extends AbstractTest
{
    public function testEmptyPromotions()
    {
        $this->clearDatabase();

        $promotionManager = new PromotionManager(
            self::getDocumentManager(),
            self::getStore(),
            self::createOrder()
        );
        
        $promotions = $promotionManager->getPromotions();
        
        $this->assertCount(0, $promotions);
    }
}
