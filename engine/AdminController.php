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

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write('Not implemented yet...');
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
}