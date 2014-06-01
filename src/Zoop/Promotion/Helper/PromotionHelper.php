<?php

namespace Zoop\Promotion\Helper;

use \DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Order\DataModel\OrderInterface;
use Zoop\Promotion\Helper\PromotionHelperChain;
use Zoop\Promotion\Discount\Compiler;
use Zoop\Promotion\Helper\PromotionManagerInterface;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\Register\Finite;

class PromotionHelper implements PromotionHelperInterface
{
    protected $promotionManager;
    
    public function __construct($promotionManager)
    {
        $this->setPromotionManager($promotionManager);
    }

    /**
     * @param PromotionManagerInterface $promotionManager
     */
    public function setPromotionManager(PromotionManagerInterface $promotionManager)
    {
        $this->promotionManager = $promotionManager;
    }

    /**
     * @return PromotionManagerInterface
     */
    public function getPromotionManager()
    {
        return $this->promotionManager;
    }

    /**
     * Applies product level discount to the product
     * 
     * @param ProductInterface $product
     */
    public function applyProductDiscount(ProductInterface $product)
    {
        
    }

    /**
     * Applies all cart level discounts to the order
     * 
     * @param Order $order
     */
    public function applyCartDiscount(OrderInterface $order)
    {
        foreach ($this->getPromotionManager()->get($order) as $promotion) {
            $discountApplied = false;
            $discountFunction = $promotion->getCartFunction();
            $break = ($promotion->getAllowCombination() === false);

            if (!empty($discountFunction)) {
                $function = create_function($this->getCartFunctionArguments() . ', &' . Compiler::VARIABLE_DISCOUNT_APPLIED, $discountFunction);

                if (!empty($function)) {
                    $discount = $function($order, $discountApplied);

                    if ($discountApplied === true) {
                        if ($this->reservePromotion($promotion)) {
                            $totalDiscount += $discount;
                            $this->addAppliedPromotion($promotion);
                        }
                    }
                }
            }

            if ($break === true && $discountApplied === true) {
                break;
            }
        }
    }

    /**
     * When a payment is canceled reset the expiry to 20mins
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setPaymentCanceled(Order $order, $expiry = '+20 Minutes')
    {
        
    }

    /**
     * Update the checkout to a little longer to coincide with inventory
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setCheckoutInProgress(Order $order, $expiry = '+40 Minutes')
    {
        
    }

    /**
     * Helper method for setting a 3hour expiry when a person is paying for an order
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setPaymentInProgress(Order $order, $expiry = '+3 Hours')
    {
        
    }

    /**
     * Reserves a promo for 20mins
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setAddToCart(Order $order, $expiry = '+20 Minutes')
    {
        
    }

    /**
     * Sets a limited promo to "used" and stores the order id.
     * Only tores the order id for unlimited promos.
     *
     * @param Order $order
     * @param PromotionInterface $promotion
     * @return boolean
     */
    public function setUsed(Order $order, PromotionInterface $promotion)
    {
        
    }

    /**
     * resets all expired limited promos
     */
    public function garbageCollection()
    {
        
    }

}
