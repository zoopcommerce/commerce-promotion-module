<?php

namespace Zoop\Promotion;

use \DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Order\DataModel\Order;
use Zoop\Promotion\PromotionChain;
use Zoop\Promotion\Discount\Compiler;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\Register\Finite;

class Promotion
{
    const DOCUMENT_FINITE_REGISTER = 'Zoop\Promotion\DataModel\Register\Finite';
    const DOCUMENT_INFINITE_REGISTER = 'Zoop\Promotion\DataModel\Register\Infinite';
    const DOCUMENT_ABSTRACT_PROMOTION = 'Zoop\Promotion\DataModel\AbstractPromotion';

    use CartVariablesTrait;
    use ProductVariablesTrait;

    private $promotionChain;
    private $order;
    private $dm;
    private $appliedPromotions;
    private $break = false;

    /**
     *
     * @return boolean
     */
    public function isOrderEmpty()
    {
        $order = $this->getOrder();
        if (!empty($order)) {
            return !$order->getHasProducts();
        }
        return true;
    }

    /**
     *
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->dm;
    }

    /**
     *
     * @param DocumentManager $dm
     */
    public function setDocumentManager(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     *
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     *
     * @return PromotionChain
     */
    public function getPromotionChain()
    {
        return $this->promotionChain;
    }

    /**
     *
     * @param PromotionChain $promotionChain
     */
    public function setPromotionChain(PromotionChain $promotionChain)
    {
        $this->promotionChain = $promotionChain;
    }

    /**
     *
     * @return array
     */
    public function getAppliedPromotions()
    {
        return $this->appliedPromotions;
    }

    /**
     *
     * @param array $appliedPromotions
     */
    public function setAppliedPromotions(array $appliedPromotions)
    {
        $this->appliedPromotions = $appliedPromotions;
    }

    /**
     *
     * @param PromotionInterface $promotion
     */
    public function addAppliedPromotion(PromotionInterface $promotion)
    {
        if (!isset($this->appliedPromotions[$promotion->getId()])) {
            $this->appliedPromotions[$promotion->getId()] = $promotion;
        }
    }

    /**
     * @return boolean
     */
    public function getBreak()
    {
        return $this->break;
    }

    /**
     * @param boolean $break
     */
    public function setBreak($break)
    {
        $this->break = (bool) $break;
    }

    public function getCartDiscount($totalQuantity, $totalPrice, $totalWholesalePrice, $totalProductPrice, $totalDiscountPrice, $totalShippingPrice, $shippingType, $shippingCountry, $cartProducts)
    {
        $totalDiscount = 0;
        $break = false;

        /* @var $promotion PromotionInterface */
        if($this->getBreak() === false) {
            foreach ($this->getPromotionChain()->getPromotions() as $promotion) {
                $discountApplied = false;
                $discountFunction = $promotion->getCartFunction();
                $break = ($promotion->getAllowCombination() === false);

                if (!empty($discountFunction)) {
                    $function = create_function($this->getCartFunctionArguments() . ', &' . Compiler::VARIABLE_DISCOUNT_APPLIED, $discountFunction);

                    if (!empty($function)) {
                        $discount = $function($totalQuantity, $totalPrice, $totalWholesalePrice, $totalProductPrice, $totalDiscountPrice, $totalShippingPrice, $shippingType, $shippingCountry, $cartProducts, $discountApplied);

                        if ($discountApplied === true) {
                            if ($this->reservePromotion($promotion)) {
                                $totalDiscount += $discount;
                                $this->addAppliedPromotion($promotion);
                            }
                        }
                    }
                }

                if ($break === true && $discountApplied === true) {
                    break;
                }
            }
        }

        return $totalDiscount;
    }

    public function getProductDiscount($productId, $productWholesalePrice, $productFullPrice)
    {
        $totalDiscount = 0;
        $break = false;

        /* @var $promotion PromotionInterface */
        foreach ($this->getPromotionChain()->getPromotions() as $promotion) {
            $discountApplied = false;
            $discountFunction = $promotion->getProductFunction();
            $break = ($promotion->getAllowCombination() === false);

            if (!empty($discountFunction)) {
                $function = create_function($this->getProductFunctionArguments() . ', &' . Compiler::VARIABLE_DISCOUNT_APPLIED, $discountFunction);

                if (!empty($function)) {
                    $discount = $function($productId, $productWholesalePrice, $productFullPrice, $discountApplied);

                    if ($discountApplied === true) {
                        if ($this->reservePromotion($promotion)) {
                            $totalDiscount += $discount;
                            $this->addAppliedPromotion($promotion);
                        }
                    }
                }
            }

            if ($break === true && $discountApplied === true) {
                $this->setBreak(true);
                break;
            }
        }

        return $totalDiscount;
    }

    public function reservePromotion(PromotionInterface $promotion, $expiry = '+20 Minutes')
    {
        $order = $this->getOrder();

        if ($promotion instanceof UnlimitedPromotion) {
            $order->addPromotion($promotion);
            return true;
        } elseif ($promotion instanceof LimitedPromotion) {
            if (!empty($order)) {
                $coupon = $order->getCoupon();
                //if the order has products
                //then we want to double check if it already has a promo registered.
                if ($this->hasExistingFiniteRegister($promotion) === false) {
                    if ($this->isOrderEmpty() === false || !empty($coupon)) {
                        //try and reserve the limited promotion
                        return $this->reserveFiniteRegister($promotion, $expiry);
                    } else {
                        //whether or not it has any available slots
                        if ($promotion->getNumberAvailable() > 0) {
                            return true;
                        }
                    }
                } else {
                    return true;
                }
            } else {
                //check if there's any more available
                if ($promotion->getNumberAvailable() > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * This allows you to clear all the current reserved promotions for a particular order
     *
     * @param string $expiry
     */
    public function clear()
    {
        $order = $this->getOrder();
        if (!empty($order)) {
            $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
                    ->update()
                    ->multiple(true)
                    ->field('state')->set(Finite::STATE_AVAILABLE)
                    ->field('order')->unsetField()
                    ->field('stateExpiry')->unsetField()
                    ->field('state')->equals(Finite::STATE_IN_CART)
                    ->field('order')->references($order)
                    ->getQuery()
                    ->execute();

            $this->updateRegistryTotals();
        }
    }

    /**
     * When a payment is canceled reset the expiry to 20mins
     *
     * @param string $expiry
     */
    public function setPaymentCanceled($expiry = '+20 Minutes')
    {
        $this->extendAllPromotionsExpiry($expiry);
    }

    /**
     * Update the checkout to a little longer to coincide with inventory
     *
     * @param string $expiry
     */
    public function setCheckoutInProgress($expiry = '+40 Minutes')
    {
        $this->extendAllPromotionsExpiry($expiry);
    }

    /**
     * Helper method for setting a 3hour expiry when a person is paying for an order
     *
     * @param string $expiry
     */
    public function setPaymentInProgress($expiry = '+3 Hours')
    {
        $this->extendAllPromotionsExpiry($expiry);
    }

    /**
     * Reserves a promo for 20mins
     *
     * @param string $expiry
     */
    public function setAddToCart($expiry = '+20 Minutes')
    {
        $this->extendAllPromotionsExpiry($expiry);
    }

    /**
     *
     * @param string $expiry
     */
    protected function extendAllPromotionsExpiry($expiry = '+3 Hours')
    {
        $promotions = $this->getPromotionChain()->getPromotions();
        if (is_array($promotions) && !empty($promotions)) {
            foreach ($promotions as $promotion) {
                if ($promotion instanceof LimitedPromotion) {
                    $this->extendExpiry($promotion, $expiry);
                }
            }
        }
    }

    /**
     * Helper method for extending an expiry while a customer is still active
     *
     * @param \Zoop\Promotion\DataModel\PromotionInterface $promotion
     * @param type $expiry
     */
    protected function extendExpiry(PromotionInterface $promotion, $expiry = '+20 Minutes')
    {
        $order = $this->getOrder();
        if (!empty($order)) {
            if ($promotion instanceof LimitedPromotion) {
                $qb = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
                        ->findAndUpdate()
                        ->returnNew();

                $qb->addAnd(
                        $qb->expr()->field('promotion')->references($promotion)
                                ->field('order')->references($order)
                );

                // Update found job
                $registry = $qb->field('stateExpiry')->set(new DateTime($expiry))
                        ->getQuery()
                        ->execute();
            }
        }
        return false;
    }

    public function setAllUsed()
    {
        $order = $this->getOrder();
        if (!empty($order)) {
            $promotions = $order->getPromotions();
            foreach ($promotions as $promotion) {
                $this->setUsed($promotion);
            }
        }
    }

    /**
     * Sets a limited promo to "used" and stores the order id.
     * Only tores the order id for unlimited promos.
     *
     * @param PromotionInterface $promotion
     * @return boolean
     */
    public function setUsed(PromotionInterface $promotion)
    {
        $order = $this->getOrder();
        if (!empty($order)) {
            if ($promotion instanceof LimitedPromotion) {
                $qb = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
                        ->findAndUpdate()
                        ->returnNew();

                $qb->addAnd(
                        $qb->expr()->field('promotion')->references($promotion)
                                ->field('state')->equals(Finite::STATE_IN_CART)
                                ->field('order')->references($order)
                );

                // Update found job
                $registry = $qb->field('state')->set(Finite::STATE_USED)
                        ->getQuery()
                        ->execute();

                if (!empty($registry)) {
                    $this->incrementPromotionUsed($promotion);
                    return true;
                }
            } elseif ($promotion instanceof UnlimitedPromotion) {
                $this->incrementPromotionUsed($promotion);
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param LimitedPromotion $promotion
     * @param string $expiry
     * @return boolean
     */
    private function incrementPromotionReserved(PromotionInterface $promotion)
    {
        if ($promotion instanceof LimitedPromotion) {
            $promotion->incrementNumberInCart();
            $promotion->decrementNumberAvailable();
            $this->save($promotion, false);
        }
    }

    /**
     *
     * @param LimitedPromotion $promotion
     * @param string $expiry
     * @return boolean
     */
    private function incrementPromotionUsed(PromotionInterface $promotion)
    {
        if ($promotion instanceof LimitedPromotion) {
            $promotion->decrementNumberInCart();
        }

        $promotion->incrementNumberUsed();
        $this->save($promotion, false);
    }

    /**
     *
     * @param LimitedPromotion $promotion
     * @param string $expiry
     * @return boolean
     */
    private function reserveFiniteRegister(LimitedPromotion $promotion, $expiry = '+20 Minutes')
    {
        $order = $this->getOrder();
        if (!empty($order)) {
            $coupon = $this->getOrder()->getCoupon();
            $orderId = $order->getId();

            if (!empty($orderId)) {
                $qb = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
                        ->findAndUpdate()
                        ->returnNew();

                // I don't think this is the right way to go about this. But
                // ODM doesn't seem to have a reference not equal to
                $qb->addAnd(
                        $qb->expr()->field('promotion')->references($promotion)
                                ->field('state')->equals(Finite::STATE_AVAILABLE)
                );

                if (!empty($orderId)) {
                    $qb->addAnd(
                            $qb->expr()->field('order.$id')->notEqual($orderId)
                    );
                }

                if (!empty($coupon)) {
                    $qb->addAnd(
                            $qb->expr()->field('coupon.code')->equals($coupon)
                    );
                }

                // Update found job
                $registry = $qb->field('state')->set(Finite::STATE_IN_CART)
                        ->field('stateExpiry')->set(new DateTime($expiry))
                        ->getQuery()
                        ->execute();

                if (!empty($registry)) {
                    $order->addPromotion($promotion);
                    $registry->setOrder($order);
                    $this->getDocumentManager()->flush();

                    //increment promotion total
                    $this->incrementPromotionReserved($promotion);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     *
     * @param LimitedPromotion $promotion
     * @param string $expiry
     * @return boolean
     */
    private function updateFiniteRegister(LimitedPromotion $promotion, $state = Finite::STATE_IN_CART, $expiry = '+20 Minutes')
    {
        $order = $this->getOrder();
        if (!empty($order)) {
            $coupon = $this->getOrder()->getCoupon();

            $qb = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
                    ->findAndUpdate()
                    ->returnNew();

            $qb->addAnd(
                    $qb->expr()->field('promotion')->references($promotion)
                            ->field('order')->references($order)
            );

            if (!empty($coupon)) {
                $qb->addAnd(
                        $qb->expr()->field('coupon.code')->equals($coupon)
                );
            }

            // Update found job
            $registry = $qb->field('state')->set($state)
                    ->field('stateExpiry')->set(new DateTime($expiry))
                    ->getQuery()
                    ->execute();

            if (!empty($registry)) {
                return true;
            }
        }
        return false;
    }

    /**
     * This could end up being a really slow way to keep tabs on the totals
     * However it still might be better than the alternative of evaluating it each
     * time we want to get a promo.
     *
     * @return boolean
     */
    private function updateRegistryTotals()
    {
        $qb = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER);

        /* @var $result Doctrine\MongoDB\ArrayIterator */
        $result = $qb->map('function () {
                        emit(
                            this.promotion.$id,
                            {count: 1, state: this.state}
                        );
                    };')
                ->reduce('function (key, values) {
                            var stateCount = {
                                    \'available\': 0,
                                    \'in-cart\': 0,
                                    \'used\': 0
                                };

                            for (index in values) {
                                stateCount[values[index].state] += values[index].count;
                            }

                            return stateCount;
                        };')
                ->finalize('function Finalize(key, reduced) {
                        var stateCount = {
                            \'available\': 0,
                            \'in-cart\': 0,
                            \'used\': 0
                        };
                        if(reduced.count && reduced.state) {
                                stateCount[reduced.state] = reduced.count;
                        } else {
                                stateCount = reduced;
                        }
                        return stateCount;
                };')
                ->getQuery()
                ->execute();
        $resultArray = $result->toArray();

        foreach ($resultArray as $result) {
            if (isset($result['value'])) {
                //update
                $totals = $result['value'];
                $qb = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_ABSTRACT_PROMOTION)
                                ->findAndUpdate()
                                ->returnNew()
                                ->field('id')->equals($result['_id']);

                if (isset($totals['available']) && is_numeric($totals['available'])) {
                    $qb->field('numberAvailable')->set($totals['available']);
                }
                if (isset($totals['in-cart']) && is_numeric($totals['in-cart'])) {
                    $qb->field('numberInCart')->set($totals['in-cart']);
                }
                if (isset($totals['used']) && is_numeric($totals['used'])) {
                    $qb->field('numberUsed')->set($totals['used']);
                }

                $qb->getQuery()
                        ->execute();
            }
        }

        return false;
    }

    /**
     *
     * @param LimitedPromotion $promotion
     * @return boolean
     */
    private function hasExistingFiniteRegister(LimitedPromotion $promotion)
    {
        $order = $this->getOrder();
        if (!empty($order)) {
            $registry = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
                    ->field('promotion')->references($promotion)
                    ->field('order')->references($order)
                    ->field('state')->in([Finite::STATE_IN_CART, Finite::STATE_USED])
                    ->getQuery()
                    ->getSingleResult();

            if (!empty($registry)) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param object $data
     */
    private function save($data, $persist = true)
    {
        if (!empty($data)) {
            if (is_array($data)) {
                foreach ($data as $d) {
                    if ($persist === true) {
                        $this->getDocumentManager()->persist($d);
                    }
                }
            } elseif ($persist === true) {
                $this->getDocumentManager()->persist($data);
            }
            $this->getDocumentManager()->flush();
        }
    }

    /**
     * resets all expired limited promos
     */
    public function garbageCollection()
    {
        $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
                ->update()
                ->multiple(true)
                ->field('state')->set(Finite::STATE_AVAILABLE)
                ->field('order')->unsetField()
                ->field('stateExpiry')->unsetField()
                ->field('state')->equals(Finite::STATE_IN_CART)
                ->field('stateExpiry')->lt(new DateTime)
                ->getQuery()
                ->execute();

        $this->updateRegistryTotals();
    }
}
