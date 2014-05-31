<?php

namespace Zoop\Promotion\Controller;

use \DateTime;
use Zend\View\Model\JsonModel;
use Zoop\Promotion\DataModel\AbstractPromotion;
use Zoop\Promotion\DataModel\LimitedPromotion;
use Zoop\Promotion\DataModel\UnlimitedPromotion;
use Zoop\Promotion\DataModel\PromotionInterface;
use Zoop\Promotion\DataModel\Register\RegisterInterface;
use Zoop\Promotion\DataModel\Register\Infinite;
use Zoop\Promotion\DataModel\Register\Finite;
use Zoop\Promotion\DataModel\Register\Coupon;

class PromotionController extends AbstractController
{
    const TYPE_LIMITED_PROMOTION = 'Limited';
    const TYPE_UNLIMITED_PROMOTION = 'Unlimited';
    const PROMOTION_DATA_MODEL = 'Zoop\Promotion\DataModel\AbstractPromotion';
    const LIMITED_PROMOTION_DATA_MODEL = 'Zoop\Promotion\DataModel\LimitedPromotion';
    const ABSTRACT_REGISTER_DATA_MODEL = 'Zoop\Promotion\DataModel\Register\AbstractRegister';
    const FINITE_REGISTER_DATA_MODEL = 'Zoop\Promotion\DataModel\Register\Finite';
    const INFINITE_REGISTER_DATA_MODEL = 'Zoop\Promotion\DataModel\Register\Infinite';

    /**
     *
     * @param string $id
     * @param boolean $serialize
     * @return PromotionInterface|string
     */
    public function get($id)
    {
        $promotion = $this->getDm()->createQueryBuilder(self::PROMOTION_DATA_MODEL)
            ->field('id')->equals($id)
            ->field('stores')->in([$this->getStoreSubDomain()])
            ->getQuery()
            ->getSingleResult();

        $results = $this->getSerializer()->toArray($promotion);
        
        return new JsonModel($results);
    }

    public function getList()
    {
        $this->getSerializer()->setMaxNestingDepth(10);

        $promos = [];
        $promotions = $this->getDm()->createQueryBuilder(self::PROMOTION_DATA_MODEL)
            ->field('stores')->in([$this->getStoreSubDomain()])
            ->getQuery()
            ->execute();

        /* @var $promotion PromotionInterface */
        foreach ($promotions as $promotion) {
            $promos[] = $this->getSerializer()->toArray($promotion);
        }

        return new JsonModel($promos);
    }

    public function create($data)
    {
        unset($data['id']);

        $data = $this->lintData($data);
        /* @var $newPromotion PromotionInterface */
        $newPromotion = $this->getUnserializer()->fromArray($data, self::PROMOTION_DATA_MODEL);

        if (!empty($newPromotion)) {
            $this->addFunctionsToPromotion($newPromotion);
            $this->save($newPromotion);

            if ($newPromotion instanceof UnlimitedPromotion) {
                $this->createRegisterFromUnlimitedPromotion($newPromotion);
            } elseif ($newPromotion instanceof LimitedPromotion) {
                $this->createRegisterFromLimitedPromotion($newPromotion);

                //update promotion uses
                $this->updateLimitedPromotionTotals($newPromotion);
            }

            return $this->getSerializer()->toJson($newPromotion);
        } else {
            return json_encode([
                'error' => true
            ]);
        }
    }

    public function update($id, $data)
    {
        $promotion = $this->get($id, false);
        if ($promotion) {
            $data = $this->lintData($data);

            if (
                ($data['type'] == self::TYPE_LIMITED_PROMOTION && $promotion instanceof UnlimitedPromotion) ||
                ($data['type'] == self::TYPE_UNLIMITED_PROMOTION && $promotion instanceof LimitedPromotion)
            ) {
                $data['numberUsed'] = (int) $promotion->getNumberUsed();

                $this->remove($id);
                return $this->create($data);
            }

            /* @var $newPromotion PromotionInterface */
            $newPromotion = $this->getUnserializer()->fromArray(
                    $data, self::PROMOTION_DATA_MODEL, $promotion
            );
            if (!empty($newPromotion)) {
                $newPromotion->setUpdatedOn(new DateTime);
                $this->addFunctionsToPromotion($newPromotion);
                $this->getDm()->flush();

                if ($newPromotion instanceof UnlimitedPromotion) {
                    $this->alterUnlimitedRegistry($newPromotion);
                } elseif ($newPromotion instanceof LimitedPromotion) {
                    $this->alterLimitedRegistry($newPromotion);

                    //update promotion uses
                    $this->updateLimitedPromotionTotals($newPromotion);
                }

                return $this->getSerializer()->toJson($newPromotion);
            } else {
                return json_encode([
                    'error' => true
                ]);
            }
        }
    }

    public function remove($id)
    {
        $this->getSerializer()->setMaxNestingDepth(0);
        /* @var $promotion PromotionInterface */
        $promotion = $this->getDm()->createQueryBuilder(self::PROMOTION_DATA_MODEL)
            ->field('stores')->in([$this->getStoreSubDomain()])
            ->field('id')->equals($id)
            ->getQuery()
            ->getSingleResult();
        if ($promotion) {
            //remove/soft delete all promotion registry entries
            $this->removeAllRegisters($promotion);

            $this->getSoftDelete()->softDelete($promotion, $this->getDm()->getClassMetadata(get_class($promotion)));
            $this->getDm()->flush();
            return json_encode(['error' => false, 'message' => 'Promotion deleted']);
        } else {
            return json_encode(['error' => true, 'message' => 'Could not delete the promotion']);
        }
    }

    private function lintData($data)
    {
        if (isset($data['limited']) && $data['limited'] == true && isset($data['limit']) && !empty($data['limit'])) {
            $data['type'] = AbstractPromotion::TYPE_LIMITED;
        } else {
            $data['type'] = AbstractPromotion::TYPE_UNLIMITED;
            $data['limit'] = null;
            $data['limited'] = false;
        }

        $data['allowCombination'] = ($data['allowCombination'] == 1) ? true : false;
        $data['stores'] = [$this->getStoreSubDomain()];

        //get coupons and limits.
        if (!isset($data['couponsMap']) || empty($data['couponsMap'])) {
            $data['couponsMap'] = [];
        }

        return $data;
    }

    /**
     *
     * @param UnlimitedPromotion $promotion
     */
    private function alterUnlimitedRegistry(UnlimitedPromotion $promotion)
    {
        $currentRegistry = $promotion->getRegistry();

        if (!$currentRegistry instanceof Infinite) {
            //create
            $this->createRegisterFromUnlimitedPromotion($promotion);
        } else {
            //update
            $couponModels = [];
            $coupons = $promotion->getCouponsMap();

            foreach ($coupons as $coupon) {
                $couponModel = new Coupon;
                $couponModel->setCode($coupon);

                $couponModels[] = $couponModel;
            }

            $currentRegistry->setCoupons($couponModels);
            $this->save($currentRegistry);
        }
    }

    /**
     *
     * @param LimitedPromotion $promotion
     */
    private function alterLimitedRegistry(LimitedPromotion $promotion)
    {
        $currentRegistry = $promotion->getRegistry();
        if (empty($currentRegistry)) {
            //create
            $this->createRegisterFromLimitedPromotion($promotion);
        } else {
            //has coupons
            if ($promotion->hasRegisterCoupons() === true) {
                if ($promotion->hasCoupons() === true) {
                    $this->adjustLimitedRegistryWithCoupons($promotion);
                } else {
                    //remove all existing
                    $this->removeAllRegisters($promotion);
                    //add all new
                    $this->createRegisterFromLimitedPromotion($promotion);
                }
            } else {
                //has coupon map
                if ($promotion->hasCoupons() === true) {
                    //remove all existing
                    $this->removeAllRegisters($promotion);
                    //add all new
                    $this->createRegisterFromLimitedPromotion($promotion);
                } else {
                    //adjust the limits
                    $this->adjustLimitedRegistryWithoutCoupons($promotion);
                }
            }
        }
    }

    /**
     *
     * @param LimitedPromotion $promotion
     */
    private function adjustLimitedRegistryWithoutCoupons(LimitedPromotion $promotion)
    {
        $newLimit = $promotion->getLimit();
        $numberOfRegistry = $promotion->getRegisterCount();

        $limit = $newLimit - $numberOfRegistry;

        if ($limit > 0) {
            //add
            $this->createFiniteRegister($promotion, $limit);
        } else {
            //remove
            $registry = $promotion->getRegistry();

            for ($i = 0; $i < abs($limit); $i++) {
                $this->removeRegister($registry[$i]);
            }
        }
    }

    /**
     *
     * @param LimitedPromotion $promotion
     */
    private function adjustLimitedRegistryWithCoupons(LimitedPromotion $promotion)
    {
        //adjust the current coupon limits
        $newCoupons = [];
        $couponAdjustments = [];
        $newLimit = $promotion->getLimit();
        $couponCount = $this->getRegisterCouponCount($promotion->getRegistry());

        foreach ($promotion->getCouponsMap() as $couponCode) {
            if (isset($couponCount[$couponCode])) {
                if ($couponCount[$couponCode] != $newLimit) {
                    $couponAdjustments[$couponCode] = (int) ($newLimit - $couponCount[$couponCode]);
                } else {
                    $couponAdjustments[$couponCode] = 0;
                }
            } else {
                //coupon doesn't exist, so create it
                $newCoupons[$couponCode] = $newLimit;
            }
        }

        //remove deleted coupons
        foreach ($couponCount as $couponCode => $limit) {
            if (!isset($newCoupons[$couponCode]) && !isset($couponAdjustments[$couponCode])) {
                $this->removeAllRegisterWithCouponCode($promotion, $couponCode);
            }
        }

        //add new coupons
        foreach ($newCoupons as $couponCode => $limit) {
            $this->createFiniteRegister($promotion, $limit, $couponCode);
        }

        //apply adjustments
        foreach ($couponAdjustments as $couponCode => $limit) {
            if ($limit != 0) {
                if ($limit > 0) {
                    //add more
                    $this->createFiniteRegister($promotion, $limit, $couponCode);
                } else {
                    //remove some
                    $this->removeRegisterWithCouponCode($promotion, abs($limit), $couponCode);
                }
            }
        }
    }

    /**
     *
     * @param LimitedPromotion $promotion
     * @param int $limit
     * @param string $couponCode
     */
    private function removeRegisterWithCouponCode(LimitedPromotion $promotion, $limit, $couponCode)
    {
        $registers = [];
        if ($promotion instanceof LimitedPromotion) {
            $registry = $promotion->getRegistry();
            /* @var $entry Finite */
            foreach ($registry as $entry) {
                $coupon = $entry->getCoupon();
                if ($coupon instanceof Coupon && $coupon->getCode() === $couponCode) {
                    $registers[] = $entry;
                    $limit--;
                }

                if ($limit === 0) {
                    break;
                }
            }
        }

        foreach ($registers as $register) {
            $this->removeRegister($register);
        }
    }

    /**
     *
     * @param LimitedPromotion $promotion
     * @param string $couponCode
     */
    private function removeAllRegisterWithCouponCode(LimitedPromotion $promotion, $couponCode)
    {
        $registers = [];
        if ($promotion instanceof LimitedPromotion) {
            $registry = $promotion->getRegistry();
            /* @var $entry Finite */
            foreach ($registry as $entry) {
                $coupon = $entry->getCoupon();
                if ($coupon instanceof Coupon && $coupon->getCode() === $couponCode) {
                    $registers[] = $entry;
                }
            }
        }

        foreach ($registers as $register) {
            $this->removeRegister($register);
        }
    }

    /**
     *
     * @param RegisterInterface $register
     */
    private function removeRegister(RegisterInterface $register)
    {
        $this->getSoftDelete()
            ->softDelete(
                $register,
                $this->getDm()->getClassMetadata(get_class($register))
            );
        $this->getDm()->flush();
    }

    /**
     *
     * @param PromotionInterface $promotion
     */
    private function removeAllRegisters(PromotionInterface $promotion)
    {
        $registers = [];
        if ($promotion instanceof UnlimitedPromotion) {
            $registers[] = $promotion->getRegistry();
        } elseif ($promotion instanceof LimitedPromotion) {
            $registry = $promotion->getRegistry();
            /* @var $entry Finite */
            foreach ($registry as $entry) {
                $registers[] = $entry;
            }
        }

        foreach ($registers as $register) {
            $this->removeRegister($register);
        }
    }

    /**
     *
     * @param array $registry
     * @return array
     */
    private function getRegisterCouponCount($registry = [])
    {
        $couponCount = [];
        $count = 0;
        /* @var $entry Finite */
        foreach ($registry as $entry) {
            $coupon = $entry->getCoupon();
            if ($coupon instanceof Coupon) {
                if (isset($couponCount[$coupon->getCode()])) {
                    $couponCount[$coupon->getCode()] ++;
                } else {
                    $couponCount[$coupon->getCode()] = 1;
                }
            }
        }
        return $couponCount;
    }

    private function createFiniteRegister(LimitedPromotion $promotion, $limit, $couponCode = null)
    {
        $registers = [];
        for ($i = 0; $i < $limit; $i++) {
            $register = new Finite;

            if (!empty($couponCode)) {
                $coupon = new Coupon;
                $coupon->setCode($couponCode);
                $register->setCoupon($coupon);
            }
            $register->setPromotion($promotion);
            $registers[] = $register;
        }
        $this->save($registers);
    }

    /**
     *
     * @param UnlimitedPromotion $promotion
     */
    private function createRegisterFromUnlimitedPromotion(UnlimitedPromotion $promotion)
    {
        $coupons = $promotion->getCouponsMap();
        $numCoupons = count($coupons);
        $register = new Infinite;

        for ($j = 0; ($j < $numCoupons) || $numCoupons == 0; $j++) {
            if (isset($coupons[$j]) && !empty($coupons[$j])) {
                $coupon = new Coupon;
                $coupon->setCode($coupons[$j]);
                $register->addCoupon($coupon);
            }
            if ($numCoupons == 0) {
                break;
            }
        }

        $register->setPromotion($promotion);
        $this->save($register);
    }

    /**
     *
     * @param LimitedPromotion $promotion
     */
    private function createRegisterFromLimitedPromotion(LimitedPromotion $promotion)
    {
        $limit = $promotion->getLimit();
        $coupons = $promotion->getCouponsMap();

        $numCoupons = count($coupons);

        for ($j = 0; $j < $numCoupons || $numCoupons == 0; $j++) {
            for ($i = 0; $i < $limit; $i++) {
                $register = new Finite;

                if (isset($coupons[$j]) && !empty($coupons[$j])) {
                    $coupon = new Coupon;
                    $coupon->setCode($coupons[$j]);
                    $register->setCoupon($coupon);
                }
                $register->setPromotion($promotion);

                $this->save($register);
            }
            if ($numCoupons == 0) {
                break;
            }
        }
    }

    private function updateLimitedPromotionTotals(LimitedPromotion $promotion)
    {
        $numberAvailable = 0;
        $numberUsed = 0;
        $numberInCart = 0;

        /* @var $registry Finite */
        foreach ($this->getRegistry($promotion) as $registry) {
            switch ($registry->getState()) {
                case Finite::STATE_AVAILABLE:
                    $numberAvailable++;
                    break;
                case Finite::STATE_IN_CART:
                    $numberInCart++;
                    break;
                case Finite::STATE_USED:
                    $numberUsed++;
                    break;
            }
        }

        $promotion->setNumberAvailable($numberAvailable);
        $promotion->setNumberInCart($numberInCart);
        $promotion->setNumberUsed($numberUsed);

        $this->save($promotion);
    }

    /**
     *
     * @param \Zoop\Promotion\DataModel\PromotionInterface $promotion
     * @return array|boolean
     */
    public function getRegistry(PromotionInterface $promotion)
    {
        $registry = [];
        $this->getSerializer()->setMaxNestingDepth(0);

        if ($promotion instanceof LimitedPromotion) {
            $model = self::FINITE_REGISTER_DATA_MODEL;
        } elseif ($promotion instanceof UnlimitedPromotion) {
            $model = self::INFINITE_REGISTER_DATA_MODEL;
        }

        $registries = $this->getDm()->createQueryBuilder($model)
            ->field('promotion')->references($promotion)
            ->getQuery()
            ->execute();

        if (!empty($registries)) {
            /* @var $register RegisterInterface */
            foreach ($registries as $register) {
                $registry[] = $register;
            }

            return $registry;
        }

        return false;
    }
}
