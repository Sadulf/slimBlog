<?php

$config = [
    'siteURI' => 'http://slim.test',
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true,
//        'routerCacheFile' => ROOT. '/cache/router.cache.php',

        'logger' => [
            'name' => 'app',
            'path' => ROOT . '/logs/app.log',
            'level' => \Monolog\Logger::DEBUG
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
            'template' => ROOT . '/template'
        ],
    ],
    'users' => [
        'admin' => '1',
    ],
    'routes' => [
        // admin routes
        'AdminController:indexAction' => [
            'path' => '/admin/',
            'action' => '\AdminController:indexAction',
            'inMenu' => 'admin',
            'menuTitle' => 'Админпанель',
            'needAuth' => true
        ],
        'AdminController:indexPageAction' => [
            'path' => '/admin/index/',
            'action' => '\AdminController:indexPageAction',
            'inMenu' => 'admin',
            'menuTitle' => 'Главная',
            'needAuth' => true
        ],
        'AdminController:categoriesAction' => [
            'path' => '/admin/categories/[page-{page}/]',
            'action' => '\AdminController:categoriesAction',
            'inMenu' => 'admin',
            'menuTitle' => 'Категории',
            'needAuth' => true
        ],
        'AdminController:categoryAction' => [
            'path' => '/admin/category/[{id}/]',
            'action' => '\AdminController:categoryAction',
            'needAuth' => true
        ],
        'AdminController:articlesAction' => [
            'path' => '/admin/articles/[page-{page}/]',
            'action' => '\AdminController:articlesAction',
            'inMenu' => 'admin',
            'menuTitle' => 'Публикации',
            'needAuth' => true
        ],
        'AdminController:articleAction' => [
            'path' => '/admin/article/{id}/',
            'action' => '\AdminController:articleAction',
            'needAuth' => true
        ],
        'AdminController:staticAction' => [
            'path' => '/admin/static/[page-{page}/]',
            'action' => '\AdminController:staticAction',
            'inMenu' => 'admin',
            'menuTitle' => 'Статичные страницы',
            'needAuth' => true
        ],
        'AdminController:staticPageAction' => [
            'path' => '/admin/static_page/{id}/',
            'action' => '\AdminController:staticPageAction',
            'needAuth' => true
        ],
        /*
                'AdminController:indexPageSaveAction' => [
                    'method' => 'POST',
                    'path' => '/admin/index/edit/',
                    'action' => '\AdminController:indexPageSaveAction',
                    'needAuth' => true
                ],
                'AdminController:categorySaveAction' => [
                    'method' => 'POST',
                    'path' => '/admin/category/{id}/',
                    'action' => '\AdminController:categorySaveAction',
                    'needAuth' => true
                ],
                'AdminController:articleSaveAction' => [
                    'method' => 'POST',
                    'path' => '/admin/article/{id}/',
                    'action' => '\AdminController:articleSaveAction',
                    'needAuth' => true
                ],*/

        // blog routes
        'MainController:categoryAction' => [
            'method' => 'GET',
            'path' => '/{id}/[page-{page}/]',
            'action' => '\MainController:categoryAction'
        ],
        'MainController:articleAction' => [
            'method' => 'GET',
            'path' => '/{id}.html',
            'action' => '\MainController:articleAction'
        ],
        'MainController:indexAction' => [
            'method' => 'GET',
            'path' => '/[page-{page}/]',
            'action' => '\MainController:indexAction'
        ]
    ]
];