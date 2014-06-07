<?php

namespace Zoop\Promotion\Discount;

use Zoop\Promotion\Discount\Discount;

class DiscountChain
{
    protected $discounts = [];

    /**
     *
     * @param Discount $discount
     */
    public function addDiscount(Discount $discount)
    {
        $this->discounts[] = $discount;
    }

    /**
     *
     * @return array
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     *
     * @param array $discounts
     */
    public function setDiscounts(array $discounts)
    {
        $this->discounts = $discounts;
    }
    
    public function getTotalDiscount()
    {
        $total = 0;
        
        /* @var $discount Discount */
        foreach($this->getDiscounts() as $discount) {
            $total += $discount->getTotalDiscount();
        }
        
        return $total;
    }
    
    public function getTotalCartDiscount()
    {
        $total = 0;
        
        /* @var $discount Discount */
        foreach($this->getDiscounts() as $discount) {
            $total += $discount->getCartDiscount();
        }
        
        return $total;
    }
    
    public function getTotalItemDiscount()
    {
        $total = 0;
        
        /* @var $discount Discount */
        foreach($this->getDiscounts() as $discount) {
            $total += $discount->getItemDiscount();
        }
        
        return $total;
    }
    
    public function getTotalShippingDiscount()
    {
        $total = 0;
        
        /* @var $discount Discount */
        foreach($this->getDiscounts() as $discount) {
            $total += $discount->getShippingDiscount();
        }
        
        return $total;
    }
}
