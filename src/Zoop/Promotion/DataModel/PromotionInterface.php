<?php

namespace Zoop\Promotion\DataModel;

use \DateTime;
use Zoop\Promotion\DataModel\Discount\DiscountInterface;

interface PromotionInterface
{
    public function getId();

    public function getLegacyId();

    public function getStores();

    public function getName();

    public function getStartDate();

    public function getEndDate();

    public function getNumberUsed();

    public function getActive();

    public function setLegacyId($legacyId);

    public function setStores(array $stores);

    public function setName($name);

    public function setStartDate(DateTime $start);

    public function setEndDate(DateTime $end);

    public function setNumberUsed($numberUsed);

    public function setActive($active);

    public function getProductIds();

    public function setProductIds($productIds);

    public function addProductId($productId);

    public function getDiscounts();

    public function setDiscounts(array $discounts);

    public function addDiscount(DiscountInterface $discount);

    public function getCartFunction();

    public function getProductFunction();

    public function setCartFunction($cartFunction);

    public function setProductFunction($productFunction);

    public function getAllowCombination();

    public function setAllowCombination($allowCombination);
}
