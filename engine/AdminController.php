<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class AdminController
{
    protected $ci;
    private $out;

    public function __construct($ci)
    {
        $this->ci = $ci;
    }

    public function indexAction($request, $response, $args)
    {
        $this->preparer();
        $this->out['menu_active'] = $this->ci->get('router')->pathFor('AdminController:indexAction');

        session_write_close();

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('admin/index.html', $this->out));
    }

    public function indexPageAction($request, $response, $args)
    {
        if ($request->isPost())
            return $this->indexPageSaveAction($request, $response, $args);

        $this->preparer();
        $this->out['menu_active'] = $this->ci->get('router')->pathFor('AdminController:indexPageAction');

        $model = new Admin($this->ci['db']);
        $this->out['data'] = $model->getIndex();
        $this->out['action'] = $this->ci->get('router')->pathFor('AdminController:indexPageAction');

        session_write_close();

        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('admin/indexPage.html', $this->out));
    }

    public function categoriesAction($request, $response, $args)
    {
        $this->preparer();
        $router = $this->ci->get('router');
        $this->out['menu_active'] = $router->pathFor('AdminController:categoriesAction');
        $model = new Admin($this->ci['db']);
        if (!isset($args['page']))
            $args['page'] = 0;

        $this->out = array_merge($this->out, $model->getCategories($args['page']));
        $this->out['add_href'] = $router->pathFor('AdminController:categoryAction');
        $this->out['this_route'] = $router->pathFor('AdminController:categoriesAction');
        foreach ($this->out['data'] as $id => $item) {
            $this->out['data'][$id]['b_edit'] = $router->pathFor('AdminController:categoryAction', ['id' => $item['id']]);
            $this->out['data'][$id]['b_delete'] = $router->pathFor('AdminController:categoryAction', ['id' => $item['id']]) . '?c=del';
            $this->out['data'][$id]['b_articles'] = $router->pathFor('AdminController:articlesAction') . '?cat=' . $item['id'];
        }

        session_write_close();
        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('admin/categories.html', $this->out));
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


    // POST UPDATE actions


    public function indexPageSaveAction($request, $response, $args)
    {
        global $_SESSION;
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $router = $this->ci->get('router');
        $route = $request->getAttribute('route');

        $data = $request->getParsedBody();
        $required_fields = [
            'title' => 'text',
            'text' => 'html',
            'meta_title' => 'text',
            'meta_description' => 'text',
            'meta_keywords' => 'text'
        ];

        $cleared_data = $this->testPostData($required_fields, $data);

        if ($cleared_data === false) {
            $_SESSION['message'] = 'Invalid data!';
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor($route->getName()));
        }

        $model = new Admin($this->ci['db']);
        if ($model->saveIndex($cleared_data) === false)
            $_SESSION['message'] = 'Error while saving your data!';
        else
            $_SESSION['message'] = 'Saved.';

        return $response
            ->withStatus(301)
            ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor($route->getName()));
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

    /** code executed for all actions in this controller
     */
    public function preparer()
    {
        global $_SESSION;

        if (session_status() == PHP_SESSION_NONE)
            session_start();

        $this->out = [];
        if (isset($_SESSION['message'])) {
            $this->out['message'] = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        $this->out['menu'] = $this->getMenu();
    }

    /** Tests POST data
     * @param $required_fields array    field=>type
     * @param $data array   array of POST data
     * @return array    valid array of parameters or FALSE
     */
    private function testPostData($required_fields, $data)
    {
        $res = [];
        foreach ($required_fields as $name => $type) {
            if (!isset($data[$name]))
                return false;
            switch ($type) {
                case 'text':
                    $res[$name] = trim(strip_tags($data[$name]));
                    break;
                case 'html':
                    $res[$name] = trim($data[$name]);
                    break;
                case 'int':
                    $res[$name] = intval($data[$name]);
                    break;
            }
        }
        return $res;
    }

    /**
     * Get menu for admin panel
     */
    private function getMenu()
    {
        $res = [];
        $router = $this->ci->get('router');
        foreach ($this->ci['routes'] as $name => $item)
            if (isset($item['inMenu']) AND $item['inMenu'] == 'admin')
                $res[$router->pathFor($name)] = $item['menuTitle'];

        return $res;
    }
}