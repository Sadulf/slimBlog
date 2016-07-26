<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../config.php';
require __DIR__.'/../engine/models/db.php';
require __DIR__.'/../engine/models/blog.php';
require __DIR__.'/../engine/MainController.php';
require __DIR__.'/../engine/AdminController.php';


// init slim

$app = new \Slim\App($config);
$container = $app->getContainer();


// init db

db::setConfig(
    'mysql:host=' . $container['settings']['db']['host'] . ';dbname='  . $container['settings']['db']['name'] . ';charset=utf8',
    $container['settings']['db']['user'], 
    $container['settings']['db']['pass']
);


// add error handlers
/*
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return \MainController::e404Action($c, $request, $response);
    };
};
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return \MainController::e500Action($c, $request, $response, $exception);
    };
};*/


// add containers

$container['logger'] = function($c) {
    // create a log channel
    $log = new \Monolog\Logger($c['settings']['logger']['name']);
    $log->pushHandler(new \Monolog\Handler\StreamHandler($c['settings']['logger']['path'], $c['settings']['logger']['level']));
    return $log;
};


$container['db'] = function () {
    $pdo = db::getInstance();
    return $pdo->getConnection();
};


// configure twig

$twig_functions = [
	'date'=> function ($arg) {
    	return date('d.m.Y',$arg);
	}
];

$container['twig'] = new Twig_Environment(
	new Twig_Loader_Filesystem($container->get('settings')['twig']['template']),
	$container->get('settings')['twig']['settings']
);

foreach($twig_functions as $name=>$func)
    $container['twig']->addFunction(new Twig_SimpleFunction($name,$func));


// add admin routes

$app->get('/admin/', '\AdminController:indexAction');
$app->get('/admin/index/', '\AdminController:indexPageAction');
$app->get('/admin/categories/', '\AdminController:categoriesAction');
$app->get('/admin/category/{id}/', '\AdminController:categoryAction');
$app->get('/admin/articles/', '\AdminController:articlesAction');
$app->get('/admin/article/{id}/', '\AdminController:articleAction');

$app->post('/admin/index/edit/', '\AdminController:indexPageSaveAction');
$app->post('/admin/category/{id}/', '\AdminController:categorySaveAction');
$app->post('/admin/article/{id}/', '\AdminController:articleSaveAction');


// add blog routes

$app->get('/{id}/[page-{page}/]', '\MainController:categoryAction');
$app->get('/{id}.html', '\MainController:articleAction');
$app->get('/[page-{page}/]', '\MainController:indexAction');