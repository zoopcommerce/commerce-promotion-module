<?php

namespace Zoop\Promotion\Discount;

use Zoop\Promotion\Discount\Discount;
use Zoop\Promotion\Discount\DiscountInterface;

class DiscountChain
{
    protected $discounts = [];

    /**
     * @param DiscountInterface $discount
     */
    public function addDiscount(DiscountInterface $discount)
    {
        $this->discounts[] = $discount;
    }

    /**
     * @return array
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * @param array $discounts
     */
    public function setDiscounts(array $discounts)
    {
        $this->discounts = $discounts;
    }

    /**
     * @return boolean
     */
    public function hasAppliedDiscounts()
    {
        $appliedDiscount = false;

        /* @var $discount Discount */
        foreach($this->getDiscounts() as $discount) {
            if($discount->isApplied()) {
                $appliedDiscount = true;
            }
        }

        return $appliedDiscount;
    }

    /**
     * @return float
     */
    public function getTotalDiscount()
    {
        $total = 0;

        /* @var $discount DiscountInterface */
        foreach($this->getDiscounts() as $discount) {
            $total += $discount->getTotalDiscount();
        }

        return $total;
    }

    /**
     * @return float
     */
    public function getTotalCartDiscount()
    {
        $total = 0;

        /* @var $discount DiscountInterface */
        foreach($this->getDiscounts() as $discount) {
            $total += $discount->getCartDiscount();
        }

        return $total;
    }

    /**
     * @return float
     */
    public function getTotalItemDiscount()
    {
        $total = 0;

        /* @var $discount DiscountInterface */
        foreach($this->getDiscounts() as $discount) {
            $total += $discount->getItemDiscount();
        }

        return $total;
    }

    /**
     * @return float
     */
    public function getTotalShippingDiscount()
    {
        $total = 0;

        /* @var $discount DiscountInterface */
        foreach($this->getDiscounts() as $discount) {
            $total += $discount->getShippingDiscount();
        }

        return $total;
    }
}
