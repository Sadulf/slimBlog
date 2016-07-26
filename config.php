<?php

$config = [
    'settings' => [
        'displayErrorDetails' => true,
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
    'routes' => [
        // admin routes
        'AdminController:indexAction'=> [
            'method'=>'GET',
            'path'=>'/admin/',
            'action'=>'\AdminController:indexAction',
            'inMenu'=>'admin',
            'menuTitle'=>'Админпанель'
        ],
        'AdminController:indexPageAction'=> [
            'method'=>'GET',
            'path'=>'/admin/index/',
            'action'=>'\AdminController:indexPageAction',
            'inMenu'=>'admin',
            'menuTitle'=>'Главная'
        ],
        'AdminController:categoriesAction'=> [
            'method'=>'GET',
            'path'=>'/admin/categories/',
            'action'=>'\AdminController:categoriesAction',
            'inMenu'=>'admin',
            'menuTitle'=>'Категории'
        ],
        'AdminController:categoryAction'=> [
            'method'=>'GET',
            'path'=>'/admin/category/{id}/',
            'action'=>'\AdminController:categoryAction'
        ],
        'AdminController:articlesAction'=> [
            'method'=>'GET',
            'path'=>'/admin/articles/',
            'action'=>'\AdminController:articlesAction',
            'inMenu'=>'admin',
            'menuTitle'=>'Публикации'
        ],
        'AdminController:articleAction'=> [
            'method'=>'GET',
            'path'=>'/admin/article/{id}/',
            'action'=>'\AdminController:articleAction'
        ],
        'AdminController:staticAction'=> [
            'method'=>'GET',
            'path'=>'/admin/static/',
            'action'=>'\AdminController:staticAction',
            'inMenu'=>'admin',
            'menuTitle'=>'Статичные страницы'
        ],
        'AdminController:staticPageAction'=> [
            'method'=>'GET',
            'path'=>'/admin/static_page/{id}/',
            'action'=>'\AdminController:staticPageAction'
        ],

        'AdminController:indexPageSaveAction'=> [
            'method'=>'POST',
            'path'=>'/admin/index/edit/',
            'action'=>'\AdminController:indexPageSaveAction'
        ],
        'AdminController:categorySaveAction'=> [
            'method'=>'POST',
            'path'=>'/admin/category/{id}/',
            'action'=>'\AdminController:categorySaveAction'
        ],
        'AdminController:articleSaveAction'=> [
            'method'=>'POST',
            'path'=>'/admin/article/{id}/',
            'action'=>'\AdminController:articleSaveAction'
        ],

        // blog routes
        'MainController:categoryAction'=> [
            'method'=>'GET',
            'path'=>'/{id}/[page-{page}/]',
            'action'=>'\MainController:categoryAction'
        ],
        'MainController:articleAction'=> [
            'method'=>'GET',
            'path'=>'/{id}.html',
            'action'=>'\MainController:articleAction'
        ],
        'MainController:indexAction'=> [
            'method'=>'GET',
            'path'=>'/[page-{page}/]',
            'action'=>'\MainController:indexAction'
        ]
    ]
];