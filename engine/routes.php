<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 27.07.2016
 * Time: 15:54
 */
$config['routes'] = [
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
        'path' => '/admin/article/[{id}/]',
        'action' => '\AdminController:articleAction',
        'needAuth' => true
    ],

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
];