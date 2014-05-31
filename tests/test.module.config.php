<?php

$mongoConnectionString = 'mongodb://localhost:27017';
$mongoZoopDatabase = 'zoop_test';
$mysqlZoopDatabase = 'zoop_test';

return [
    'doctrine' => [
        'odm' => [
            'connection' => [
                'commerce' => [
                    'dbname' => $mongoZoopDatabase,
                    'connectionString' => $mongoConnectionString,
                ],
            ],
            'configuration' => [
                'commerce' => [
                    'metadata_cache' => 'doctrine.cache.array',
                    'default_db' => $mongoZoopDatabase,
                    'generate_proxies' => true,
                    'proxy_dir' => __DIR__ . '/../data/proxies',
                    'generate_hydrators' => true,
                    'hydrator_dir' => __DIR__ . '/../data/hydrators',
                ]
            ],
        ],
    ],
    'zoop' => [
        'aws' => [
            'key' => 'AKIAJE2QFIBMYF5V5MUQ',
            'secret' => '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',
            's3' => [
                'buckets' => [
                    'test' => 'zoop-web-assets-test',
                ],
                'endpoint' => [
                    'test' => 'https://zoop-web-assets-test.s3.amazonaws.com',
                ],
            ],
        ],
        'db' => [
            'host' => 'localhost',
            'database' => $mysqlZoopDatabase,
            'username' => 'zoop',
            'password' => 'yourtown1',
            'port' => 3306,
        ],
        'cache' => [
            'handler' => 'mongodb',
            'mongodb' => [
                'connectionString' => $mongoConnectionString,
                'options' => [
                    'database' => $mongoZoopDatabase,
                    'collection' => 'Cache',
                ]
            ],
        ],
        'sendgrid' => [
            'username' => '',
            'password' => ''
        ],
        'session' => [
            'ttl' => (60 * 60 * 3), //3 hours
            'handler' => 'mongodb',
            'mongodb' => [
                'connectionString' => $mongoConnectionString,
                'options' => [
                    'database' => $mongoZoopDatabase,
                    'collection' => 'Session',
                    'saveOptions' => [
                        'w' => 1
                    ]
                ]
            ]
        ],
    ]
];
