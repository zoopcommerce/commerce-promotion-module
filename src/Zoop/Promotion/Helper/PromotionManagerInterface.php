<?php

namespace Zoop\Promotion\Helper;

use Zoop\Order\DataModel\OrderInterface;

interface PromotionManagerInterface
{
    /**
     * Returns all promotions for the current order and session
     *
     * @param OrderInterface $order
     */
    public function get(OrderInterface $order);

    /**
     * Clears promotion cache
     */
    public function clear();
}
