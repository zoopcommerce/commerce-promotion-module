<?php

return array(
    'zoop' => [
        'shard' => [
            'manifest' => [
                'commerce' => [
                    'documents' => [
                        'Zoop\Promotion\DataModel' => __DIR__ . '/../src/Zoop/Promotion/DataModel'
                    ]
                ]
            ]
        ],
    ],
    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
            'zoop.commerce.promotion' => 'Zoop\Promotion\Service\PromotionFactory',
            'zoop.commerce.promotion.controller' => 'Zoop\Promotion\Service\PromotionControllerFactory',
            'zoop.commerce.promotion.products.controller' => 'Zoop\Promotion\Service\PromotionProductsControllerFactory',
            'zoop.commerce.promotion.chain' => 'Zoop\Promotion\Service\PromotionChainFactory',
        ],
    ],
);
