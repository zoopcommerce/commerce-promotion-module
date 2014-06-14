<?php

namespace Zoop\Promotion\Discount;

use Zoop\Promotion\Discount\DiscountInterface;
use Zoop\Promotion\Discount\AbstractDiscount;
use Zoop\Order\DataModel\Item\ItemInterface;

class CartDiscount extends AbstractDiscount implements DiscountInterface
{
    protected $item;
    
    /**
     * @return ItemInterface
     */
    public function getItem()
    {
        return $this->item;
    }
    
    /**
     *
     * @param ItemInterface $item
     */
    public function setItem(ItemInterface $item)
    {
        $this->item = $item;
    }
}
