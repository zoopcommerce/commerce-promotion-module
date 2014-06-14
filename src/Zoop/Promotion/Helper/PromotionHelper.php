<?php

namespace Zoop\Promotion\Helper;

use \DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Order\DataModel\OrderInterface;
use Zoop\Product\DataModel\ProductInterface;
use Zoop\Promotion\CartVariablesTrait;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Promotion\Helper\PromotionManagerInterface;
use Zoop\Promotion\ProductVariablesTrait;
use Zoop\Promotion\Discount\DiscountChain;
use Zoop\Promotion\Discount\Discount;
use Zoop\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\Register\Finite;

class PromotionHelper implements PromotionHelperInterface
{
    use CartVariablesTrait;
    use ProductVariablesTrait;
    
    const DOCUMENT_FINITE_REGISTER = 'Zoop\Promotion\DataModel\Register\Finite';
    const DOCUMENT_INFINITE_REGISTER = 'Zoop\Promotion\DataModel\Register\Infinite';
    const DOCUMENT_ABSTRACT_PROMOTION = 'Zoop\Promotion\DataModel\AbstractPromotion';
    
    protected $promotionManager;
    protected $dm;

    public function __construct($promotionManager)
    {
        $this->setPromotionManager($promotionManager);
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
     * @param PromotionManagerInterface $promotionManager
     */
    public function setPromotionManager(PromotionManagerInterface $promotionManager)
    {
        $this->promotionManager = $promotionManager;
    }

    /**
     * @return PromotionManagerInterface
     */
    public function getPromotionManager()
    {
        return $this->promotionManager;
    }

    /**
     * Applies product level discount to the product
     *
     * @param ProductInterface $product
     */
    public function applyProductDiscount(ProductInterface $product)
    {

    }

    /**
     * Applies all cart level discounts to the order
     *
     * @param Order $order
     */
    public function applyCartDiscount(OrderInterface $order)
    {
        /* @var $promotion PromotionInterface */
        foreach ($this->getPromotionManager()->get($order) as $promotion) {
            $discountApplied = false;
            $discountFunction = $promotion->getCartFunction();
            $break = ($promotion->getAllowCombination() === false);

            if (!empty($discountFunction)) {
                $function = create_function($this->getCartFunctionArguments(), $discountFunction);

                if (!empty($function)) {
                    /* @var $discountChain DiscountChain */
                    $discountChain = $function($order);
                    
                    if($discountChain->hasAppliedDiscounts() === true) {
                        //try to reserve the promotion
                        $whereHere = 'yeew';
                    }
                }
            }

            if ($break === true && $discountApplied === true) {
                break;
            }
        }
    }
    
    public function reservePromotion(OrderInterface $order = null, PromotionInterface $promotion, $expiry = '+20 Minutes')
    {
        if ($promotion instanceof UnlimitedPromotion) {
            if (!empty($order)) {
                $order->addPromotion($promotion);
            }
            return true;
        } elseif ($promotion instanceof LimitedPromotion) {
            if (!empty($order)) {
                //then we want to double check if it already has a promo registered.
                if ($this->hasExistingFiniteRegister($order, $promotion) === false) {
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
     * @param Order $order
     * @param LimitedPromotion $promotion
     * @param string $expiry
     * @return boolean
     */
    private function reserveFiniteRegister(Order $order, LimitedPromotion $promotion, $expiry = '+20 Minutes')
    {
        $coupon = $order->getCoupon();
        $orderId = $order->getId();

        if (!empty($orderId)) {
            $qb = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
                ->findAndUpdate()
                ->returnNew();

            $qb->addAnd(
                $qb->expr()->field('promotion')->references($promotion)
                    ->field('state')->equals(Finite::STATE_AVAILABLE)
            );

            $qb->addAnd(
                $qb->expr()->field('order.$id')->notEqual($orderId)
            );

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

                $this->incrementPromotionReserved($promotion);
                return true;
            }
        }
        return false;
    }

    /**
     * Checks to see if an order contains the passed promotion already
     * 
     * @param Order $order
     * @param LimitedPromotion $promotion
     * @return boolean
     */
    private function hasExistingFiniteRegister(Order $order, LimitedPromotion $promotion)
    {
        $registry = $this->getDocumentManager()->createQueryBuilder(self::DOCUMENT_FINITE_REGISTER)
            ->field('promotion')->references($promotion)
            ->field('order')->references($order)
            ->field('state')->in([Finite::STATE_IN_CART, Finite::STATE_USED])
            ->getQuery()
            ->getSingleResult();

        if (!empty($registry)) {
            return true;
        }
        
        return false;
    }   

    /**
     * @param PromotionInterface $promotion
     */
    private function incrementPromotionReserved(PromotionInterface $promotion)
    {
        if ($promotion instanceof LimitedPromotion) {
            $promotion->incrementNumberInCart();
            $promotion->decrementNumberAvailable();

            if ($this->getDocumentManager()->contains($promotion)) {
                $this->getDocumentManager()->persist($promotion);
            }
            $this->getDocumentManager()->flush($promotion);
        }
    }

    /**
     * @param PromotionInterface $promotion
     */
    private function incrementPromotionUsed(PromotionInterface $promotion)
    {
        if ($promotion instanceof LimitedPromotion) {
            $promotion->decrementNumberInCart();
        }

        $promotion->incrementNumberUsed();
        
        if($this->getDocumentManager()->contains($promotion)) {
            $this->getDocumentManager()->persist($promotion);
        }
        $this->getDocumentManager()->flush($promotion);
    }

    /**
     * When a payment is canceled reset the expiry to 20mins
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setPaymentCanceled(Order $order, $expiry = '+20 Minutes')
    {

    }

    /**
     * Update the checkout to a little longer to coincide with inventory
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setCheckoutInProgress(Order $order, $expiry = '+40 Minutes')
    {

    }

    /**
     * Helper method for setting a 3hour expiry when a person is paying for an order
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setPaymentInProgress(Order $order, $expiry = '+3 Hours')
    {

    }

    /**
     * Reserves a promo for 20mins
     *
     * @param Order $order
     * @param string $expiry
     * @return boolean
     */
    public function setAddToCart(Order $order, $expiry = '+20 Minutes')
    {

    }

    /**
     * Sets a limited promo to "used" and stores the order id.
     * Only tores the order id for unlimited promos.
     *
     * @param Order $order
     * @param PromotionInterface $promotion
     * @return boolean
     */
    public function setUsed(Order $order, PromotionInterface $promotion)
    {

    }

    /**
     * resets all expired limited promos
     */
    public function garbageCollection()
    {

    }

}
