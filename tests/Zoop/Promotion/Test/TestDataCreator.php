<?php

namespace Zoop\Promotion\Test;

use \DateTime;
use \MongoId;
use \MongoDate;

class TestDataCreator
{
    const STORE_NAME = 'teststore';
    const DIR = '/Assets/';

    public function createAll()
    {
        $store = $this->createStore();
        $this->createJson('Store', $store);
        
        $order = $this->createOrder();
        $this->createJson('Order', $order);
        
        $limited = $this->createLimitedPromotion();
        $this->createJson('Limited/LimitedPromotion', $limited);
        
        $finite = $this->createFiniteRegister();
        $this->createJson('Limited/FiniteRegister', $finite);
        
        $unlimited = $this->createUnlimitedPromotion();
        $this->createJson('Unlimited/UnlimitedPromotion', $unlimited);
        
        $infinite = $this->createInfiniteRegister();
        $this->createJson('Unlimited/InfiniteRegister', $infinite);
    }

    protected function createJson($fileName, $data)
    {
        file_put_contents(__DIR__ . self::DIR . $fileName . '.json', json_encode($data));
    }

    public function createLimitedPromotion(
        $limit = 0,
        $available = 0,
        $inCart = 0,
        $used = 0,
        DateTime $startDate = null,
        DateTime $endDate = null,
        $couponCode = null)
    {
        $data = [
            'legacyId' => 1,
            'stores' => [
                self::STORE_NAME
            ],
            'name' => 'Limited Promotion',
            'startDate' => !is_null($startDate) ? new MongoDate($startDate->getTimestamp()) : null,
            'endDate' => !is_null($endDate) ? new MongoDate($endDate->getTimestamp()) : null,
            'productIds' => [],
            'discounts' => [],
            'couponsMap' => !empty($couponCode) ? [$couponCode] : [],
            'cartFunction' => null,
            'productFunction' => null,
            'allowCombination' => true,
            'orders' => [],
            'active' => true,
            'limited' => true,
            'limit' => $limit,
            'numberAvailable' => $available,
            'numberInCart' => $inCart,
            'numberUsed' => $used,
            'limit' => $limit,
        ];
        
        return $data;
    }

    public function createUnlimitedPromotion(
        $used = 0,
        DateTime $startDate = null,
        DateTime $endDate = null,
        $couponCode = null)
    {
        $data = [
            'legacyId' => 2,
            'stores' => [
                self::STORE_NAME
            ],
            'name' => 'Unlimited Promotion',
            'startDate' => !is_null($startDate) ? new MongoDate($startDate->getTimestamp()) : null,
            'endDate' => !is_null($endDate) ? new MongoDate($endDate->getTimestamp()) : null,
            'productIds' => [],
            'discounts' => [],
            'couponsMap' => !empty($couponCode) ? [$couponCode] : [],
            'cartFunction' => null,
            'productFunction' => null,
            'allowCombination' => true,
            'orders' => [],
            'active' => true,
            'limited' => false,
            'numberUsed' => $used,
        ];
        
        return $data;
    }

    public function createFiniteRegister()
    {
        $data = [
            'stores' => [
                self::STORE_NAME
            ],
            'state' => 'available',
        ];
        
        return $data;
    }

    public function createInfiniteRegister()
    {
        $data = [
            'stores' => [
                self::STORE_NAME
            ],
            'state' => 'available',
        ];
        
        return $data;
    }

    public function createStore()
    {
        $data = [
            'slug' => self::STORE_NAME,
            'name' => 'Test',
            'subdomain' => self::STORE_NAME,
            'email' => 'test@teststore.com'
        ];

        return $data;
    }

    public function createOrder()
    {
        $data = [
            'legacyId' => 1,
            'store' => self::STORE_NAME,
            'email' => 'testorder@order.com',
            'firstName' => 'Oscar',
            'lastName' => 'Le` Grouch',
            'address' => [
                'line1' => '1 Sesame Street',
                'line2' => null,
                'city' => 'Cartoon',
                'state' => 'VIC',
                'postcode' => '3000',
                'country' => 'AU',
            ],
            'phone' => null,
            'promotions' => [],
            'promotionRegistry' => [],
            'total' => [
                'shippingPrice' => 10,
                'productWholesalePrice' => 40,
                'productListPrice' => 100,
                'productQuantity' => 2,
                'discountPrice' => 10,
                'taxIncluded' => 9.09,
                'orderPrice' => 100,
                'currency' => [
                    'code' => 'AUD',
                    'name' => 'Australian Dollar',
                    'symbol' => '$',
                ]
            ],
            'items' => [
                [
                    'type' => 'SingleItem',
                    'legacyId' => 1,
                    'brand' => 'Garbarge',
                    'name' => 'Lid',
                    'imageSets' => [],
                    'price' => [
                        'unit' => [
                            'wholesale' => 20,
                            'list' => 50,
                            'sale' => 45,
                            'subTotal' => 45,
                            'productDiscount' => 5,
                            'cartDiscount' => 0,
                            'shippingDiscount' => 0,
                            'shipping' => 5,
                            'taxIncluded' => 4.09,
                        ],
                        'total' => [
                            'wholesale' => 40,
                            'list' => 100,
                            'sale' => 90,
                            'subTotal' => 90,
                            'productDiscount' => 10,
                            'cartDiscount' => 0,
                            'shippingDiscount' => 0,
                            'shipping' => 10,
                            'taxIncluded' => 8.18,
                        ]
                    ],
                    'sku' => [
                        'type' => 'PhysicalSku',
                        'legacyId' => 2,
                        'suppliers' => [],
//                        'inventory' => [],
                        'options' => [
                            [
                                'type' => 'Dropdown',
                                'label' => 'Size / Option',
                                'value' => 'Small',
                            ]
                        ],
                        'dimensions' => [
                            'weight' => 10,
                            'width' => 10,
                            'height' => 10,
                            'depth' => 1,
                        ]
                    ],
                    'state' => 'active',
                    'quantity' => 2,
                ]
            ],
            'shippingMethod' => null,
            'paymentMethod' => null,
            'state' => 'in-progress',
            'history' => [
                [
                    'state' => 'in-progress'
                ]
            ],
            'commission' => [
                'amount' => 0,
                'charged' => 0
            ],
            'coupon' => null,
            'invoiceSent' => false,
            'isWaitingForPayment' => false,
            'isComplete' => false,
            'invoiceSent' => false,
            'dateCompleted' => null,
            'hasProducts' => false,
        ];

        return $data;
    }
}
