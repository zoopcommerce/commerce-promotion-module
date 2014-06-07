<?php

namespace Zoop\Promotion\Helper;

use Zoop\Promotion\Helper\PromotionManagerInterface;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Order\DataModel\OrderInterface;
use Zoop\Product\DataModel\ProductInterface;

interface PromotionHelperInterface
{
    /**
     * @param PromotionManagerInterface $promotionManager
     */
    public function setPromotionManager(PromotionManagerInterface $promotionManager);

    /**
     * @return PromotionManagerInterface
     */
    public function getPromotionManager();

    /**
     * Applies product level discount to the product
     *
     * @param ProductInterface $product
     */
    public function applyProductDiscount(ProductInterface $product);

    /**
     * Applies all cart level discounts to the order
     *
     * @param Order $order
     */
    public function applyCartDiscount(OrderInterface $order);

    /**
     * When a payment is canceled reset the expiry to 20mins
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setPaymentCanceled(Order $order, $expiry = '+20 Minutes');

    /**
     * Update the checkout to a little longer to coincide with inventory
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setCheckoutInProgress(Order $order, $expiry = '+40 Minutes');

    /**
     * Helper method for setting a 3hour expiry when a person is paying for an order
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setPaymentInProgress(Order $order, $expiry = '+3 Hours');

    /**
     * Reserves a promo for 20mins
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setAddToCart(Order $order, $expiry = '+20 Minutes');

    /**
     * Sets a limited promo to "used" and stores the order id.
     * Only tores the order id for unlimited promos.
     *
     * @param Order $order
     * @param PromotionInterface $promotion
     * @return boolean
     */
    public function setUsed(Order $order, PromotionInterface $promotion);

    /**
     * resets all expired limited promos
     */
    public function garbageCollection();
}
