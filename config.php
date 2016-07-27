<?php

$config = [
    'siteURI' => 'http://slim.test',
    'users' => [
        'admin' => '1',
    ],
    'settings' => [
        'db' => [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'name' => 'test'
        ],
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
        'routerCacheFile' => ROOT . '/cache/router.cache.php',

        'logger' => [
            'name' => 'app',
            'path' => ROOT . '/logs/app.log',
            'level' => \Monolog\Logger::DEBUG // set to ERROR in production
        ],

        'twig' => [
            'settings' => [
                'debug' => true,
                'cache' => ROOT . '/cache',
            ],
            'template' => ROOT . '/template'
        ],
    ]
];