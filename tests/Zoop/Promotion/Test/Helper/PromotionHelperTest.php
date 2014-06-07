<?php

namespace Zoop\Promotion\Test\Helper;

use \DateTime;
use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Helper\PromotionHelper;
use Zoop\Promotion\Helper\PromotionManager;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\UnlimitedPromotion;

class PromotionHelperTest extends AbstractTest
{
    protected static $promotionHelper;

    public function testApplyCartDiscount()
    {
        $ph = $this->getPromotionHelper();

        $order = self::createOrder();

        $ph->applyCartDiscount($order);
    }

    /**
     * @return PromotionHelper
     */
    protected function getPromotionHelper()
    {
        if(!isset(self::$promotionHelper)) {
            $promotionManager = new PromotionManager(
                self::getDocumentManager(),
                self::getStore()
            );

            self::$promotionHelper = new PromotionHelper($promotionManager);
        }

        return self::$promotionHelper;
    }
}
