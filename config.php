<?php

$config = [
    'settings' => [
        'displayErrorDetails' => true,
//        'routerCacheFile' => ROOT. '/cache/router.cache.php',

        'logger' => [
            'name' => 'app',
            'path' => ROOT. '/logs/app.log',
            'level'=> \Monolog\Logger::DEBUG
        ],

        'db' => [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'name' => 'test',

        ],

        'twig' => [
    		'settings' => [
    			'debug' => true,
 //   			'cache' => ROOT.'/cache',
    		],
    		'template' => ROOT.'/template'
        ],
    ],
];