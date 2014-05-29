<?php

return [
    'modules' => [
        'Zoop\MaggottModule',
        'DoctrineModule',
        'DoctrineMongoODMModule',
        'Zoop\ShardModule',
        'Zoop\Common',
        'Zoop\Payment',
        'Zoop\Product',
        'Zoop\Promotion',
        'Zoop\Order',
        'Zoop\Store'
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/../config/module.config.php',
            __DIR__ . '/test.module.config.php',
        ],
    ],
];
