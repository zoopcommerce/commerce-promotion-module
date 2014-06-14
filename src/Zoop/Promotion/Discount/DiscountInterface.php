<?php

namespace Zoop\Promotion\Discount;

interface DiscountInterface
{
    /**
     * @return boolean
     */
    public function isApplied();

    /**
     * @return float
     */
    public function getTotalDiscount();

    /**
     * @return float
     */
    public function getCartDiscount();

    /**
     * @return float
     */
    public function getShippingDiscount();

    /**
     * @return float
     */
    public function getItemDiscount();

    /**
     * @param boolean $isApplied
     */
    public function setIsApplied($isApplied);

    /**
     * @param float $cartDiscount
     */
    public function setCartDiscount($cartDiscount);

    /**
     * @param float $shippingDiscount
     */
    public function setShippingDiscount($shippingDiscount);

    /**
     * @param float $itemDiscount
     */
    public function setItemDiscount($itemDiscount);
}
