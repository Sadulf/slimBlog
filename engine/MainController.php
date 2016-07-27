<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class MainController
{
    protected $ci;

    public function __construct($ci)
    {
        $this->ci = $ci;
    }

    public function indexAction($request, $response, $args)
    {
        $blog = new Blog($this->ci['db']);
        $out = $blog->getIndex();

        if (!is_array($out))
            return self::e404Action($this->ci, $request, $response);

        $out['menu'] = $blog->getMenu();
        $out['menu_selected'] = '/';

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('article.html', $out));
    }

    public function categoryAction($request, $response, $args)
    {
        $blog = new Blog($this->ci['db']);
        if (!isset($args['page']))
            $args['page'] = 0;

        $out = $blog->getCategory($args['id'], $args['page']);

        if (!is_array($out))
            return self::e404Action($this->ci, $request, $response);

        $out['menu'] = $blog->getMenu();
        $out['menu_selected'] = '/' . $args['id'] . '/';

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('category.html', $out));
    }

    public function articleAction($request, $response, $args)
    {
        $blog = new Blog($this->ci['db']);
        $out = $blog->getArticle($args['id']);

        if (!is_array($out))
            return self::e404Action($this->ci, $request, $response);

        $out['menu'] = $blog->getMenu();
        $out['menu_selected'] = '/' . $args['id'] . '.html';

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('article.html', $out));
    }

}