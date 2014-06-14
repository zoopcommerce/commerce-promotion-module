<?php

namespace Zoop\Promotion\Discount;

use Zoop\Promotion\Discount\DiscountInterface;
use Zoop\Promotion\Discount\AbstractDiscount;
use Zoop\Product\DataModel\ProductInterface;

class ProductDiscount extends AbstractDiscount implements DiscountInterface
{
    protected $product;
    
    /**
     * 
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * 
     * @param ProductInterface $product
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;
    }
}
