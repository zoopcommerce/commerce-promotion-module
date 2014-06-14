<?php

namespace Zoop\Promotion\Discount;

abstract class AbstractDiscount
{
    protected $isApplied = false;
    protected $cartDiscount = 0;
    protected $shippingDiscount = 0;
    protected $itemDiscount = 0;

    /**
     * @return boolean
     */
    public function isApplied()
    {
        return $this->isApplied;
    }

    /**
     * @return float
     */
    public function getTotalDiscount()
    {
        return $this->getCartDiscount() +
            $this->getItemDiscount() +
            $this->getShippingDiscount();
    }

    /**
     * @return float
     */
    public function getCartDiscount()
    {
        return $this->cartDiscount;
    }

    /**
     * @return float
     */
    public function getShippingDiscount()
    {
        return $this->shippingDiscount;
    }

    /**
     * @return float
     */
    public function getItemDiscount()
    {
        return $this->itemDiscount;
    }

    /**
     * @param boolean $isApplied
     */
    public function setIsApplied($isApplied)
    {
        $this->isApplied = (boolean) $isApplied;
    }

    /**
     * @param float $cartDiscount
     */
    public function setCartDiscount($cartDiscount)
    {
        $this->cartDiscount = (float) $cartDiscount;
    }

    /**
     * @param float $shippingDiscount
     */
    public function setShippingDiscount($shippingDiscount)
    {
        $this->shippingDiscount = (float) $shippingDiscount;
    }

    /**
     * @param float $itemDiscount
     */
    public function setItemDiscount($itemDiscount)
    {
        $this->itemDiscount = (float) $itemDiscount;
    }
}
