<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class MainController {
   protected $ci;

   public function __construct($ci) {
       $this->ci = $ci;
   }
   
   public function indexAction($request, $response, $args) {
        $blog = new Blog($this->ci['db']);
        $out=$blog->getIndex();

        if(!is_array($out))
        	return self::e404Action($this->ci,$request, $response);

        $out['menu']=$blog->getMenu();
        $out['menu_selected']='/';

        return $this->ci['response']
                     ->withHeader('Content-Type', 'text/html')
                     ->write($this->ci->get('twig')->render('article.html', $out));
   }
   
   public function categoryAction($request, $response, $args) {
        $blog = new Blog($this->ci['db']);
        if(!isset($args['page']))
        	$args['page']=0;

        $out=$blog->getCategory($args['id'],$args['page']);
        
        if(!is_array($out))
        	return self::e404Action($this->ci,$request, $response);

        $out['menu']=$blog->getMenu();
        $out['menu_selected']='/'.$args['id'].'/';
        
        return $this->ci['response']
                     ->withHeader('Content-Type', 'text/html')
                     ->write($this->ci->get('twig')->render('category.html', $out));
   }
   
   public function articleAction($request, $response, $args) {
        $blog = new Blog($this->ci['db']);
        $out=$blog->getArticle($args['id']);

        if(!is_array($out))
        	return self::e404Action($this->ci,$request, $response);

        $out['menu']=$blog->getMenu();
        $out['menu_selected']='/'.$args['id'].'.html';

        return $this->ci['response']
                     ->withHeader('Content-Type', 'text/html')
                     ->write($this->ci->get('twig')->render('article.html', $out));
   }


   // error handlers
   
   public static function e404Action($c, $request, $response) {
        $blog = new Blog($c['db']);
        $out=[
        	'code'=>'404',
        	'message'=>'Page not found',
        	'debug_data'=>'',
        	'menu_selected'=>'',
        	'menu'=>$blog->getMenu()
        ];

        return $c['response']->withStatus(404)
                     ->withHeader('Content-Type', 'text/html')
                     ->write($c->get('twig')->render('error.html', $out));
   }
   public static function e500Action($c, $request, $response, $exception) {
        $blog = new Blog($c['db']);
        $out=[
        	'code'=>'500',
        	'message'=>'Internal server error',
        	'debug_data'=>'',
        	'menu_selected'=>'',
        	'menu'=>$blog->getMenu()
        ];

        return $c['response']->withStatus(500)
                     ->withHeader('Content-Type', 'text/html')
                     ->write($c->get('twig')->render('error.html', $out));
   }
}