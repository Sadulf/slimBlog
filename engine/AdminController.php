<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class AdminController
{
    protected $ci;

    public function __construct($ci)
    {
        $this->ci = $ci;
    }

    public function indexAction($request, $response, $args)
    {

        // TODO

        $out = [];
        $out['menu'] = $this->getMenu();
        $out['menu_active'] = '/admin/';
        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('admin/index.html', $out));
    }

    public function indexPageAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function categoriesAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function categoryAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function articlesAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function articleAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function staticAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function staticPageAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function indexPageSaveAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function categorySaveAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }

    public function articleSaveAction($request, $response, $args)
    {

        // TODO

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
    }



    /**
     * Get menu for admin panel
     */
    public function getMenu()
    {
        $res = [];
        $router = $this->ci->get('router');
        foreach ($this->ci['routes'] as $name => $item)
            if (isset($item['inMenu']) AND $item['inMenu'] == 'admin')
                $res[$router->pathFor($name)] = $item['menuTitle'];

        return $res;
    }
}