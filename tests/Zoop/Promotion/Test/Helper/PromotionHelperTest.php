<?php

namespace Zoop\Promotion\Test\Helper;

use \DateTime;
use Zoop\Promotion\Test\AbstractTest;
use Zoop\Promotion\Helper\PromotionHelper;
use Zoop\Promotion\Helper\PromotionManager;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Promotion\DataModel\Discount\FixedAmountOff;

class PromotionHelperTest extends AbstractTest
{
    protected static $promotionHelper;

    public function testApplyCartDiscount()
    {
        $this->clearDatabase();
        $ph = $this->getPromotionHelper();

        $order = self::createOrder();
        
        $discount = new FixedAmountOff;
        $variable = new \Zoop\Promotion\DataModel\Discount\Variable\Order;
        $discount->setVariable($variable);
        $discount->setValue(10);
        $discount->setLevel('Cart');

        self::createLimitedPromotion(1, 1, 0, 0, null, null, [], true, $discount);

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
