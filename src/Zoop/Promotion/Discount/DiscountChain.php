<?php

namespace Zoop\Promotion\Discount;

use Zoop\Promotion\DataModel\Discount\DiscountInterface;

class DiscountChain
{

    private $discounts = [];

    /**
     *
     * @param DiscountInterface $discounts
     */
    public function addDiscount(DiscountInterface $discounts)
    {
        $this->discounts[] = $discounts;
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

}
