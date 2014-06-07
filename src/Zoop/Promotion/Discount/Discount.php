<?php

namespace Zoop\Promotion\Discount;

use Zoop\Order\DataModel\Item\ItemInterface;

class Discount
{
    protected $isApplied = false;
    protected $cartDiscount = 0;
    protected $shippingDiscount = 0;
    protected $itemDiscount = 0;
    protected $items = [];

    /**
     *
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
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     *
     * @param boolean $isApplied
     */
    public function setIsApplied($isApplied)
    {
        $this->isApplied = (boolean) $isApplied;
    }

    /**
     *
     * @param float $cartDiscount
     */
    public function setCartDiscount($cartDiscount)
    {
        $this->cartDiscount = (float) $cartDiscount;
    }

    /**
     *
     * @param float $shippingDiscount
     */
    public function setShippingDiscount($shippingDiscount)
    {
        $this->shippingDiscount = (float) $shippingDiscount;
    }

    /**
     *
     * @param float $itemDiscount
     */
    public function setItemDiscount($itemDiscount)
    {
        $this->itemDiscount = (float) $itemDiscount;
    }

    /**
     *
     * @param array $items
     */
    public function setItems(array $items = [])
    {
        $this->items = $items;
    }

    /**
     *
     * @param ItemInterface $item
     */
    public function addItem(ItemInterface $item)
    {
        if(!in_array($item, $this->items)) {
            $this->items[] = $item;
        }
    }
}
