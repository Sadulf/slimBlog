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

        $model = new AdminIndex($this->ci['db']);
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
        $model = new AdminCategories($this->ci['db']);
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
        if ($request->isPost())
            return $this->categorySaveAction($request, $response, $args);

        $cmd = $request->getQueryParam('c');
        $router = $this->ci->get('router');

        if (isset($args['id']) AND $cmd == 'del') {
            // delete category $args['id'], redirect to categoriesAction
            global $_SESSION;
            if (session_status() == PHP_SESSION_NONE)
                session_start();
            $model = new AdminCategories($this->ci['db']);

            $_SESSION['message'] = $model->categoryDelete($args['id']);

            session_write_close();
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor('AdminController:categoriesAction'));
        }

        $this->preparer();
        $this->out['menu_active'] = $router->pathFor('AdminController:categoriesAction');
        $model = new AdminCategories($this->ci['db']);

        if (isset($args['id'])) {
            // edit category $args['id']
            $this->out['data'] = $model->categoryEdit($args['id']);
            $this->out['action'] = $this->ci->get('router')->pathFor('AdminController:categoryAction', ['id' => $args['id']]);
        } else {
            // add category
            $this->out['data'] = $model->categoryAdd();
            $this->out['action'] = $this->ci->get('router')->pathFor('AdminController:categoryAction');
        }

        session_write_close();
        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('admin/category.html', $this->out));
    }

    public function articlesAction($request, $response, $args)
    {
        $this->preparer();
        $category = $request->getQueryParam('cat');
        $router = $this->ci->get('router');
        $this->out['menu_active'] = $router->pathFor('AdminController:articlesAction');
        $model = new AdminArticles($this->ci['db']);
        if (!isset($args['page']))
            $args['page'] = 0;

        $this->out = array_merge($this->out, $model->getArticles($category,$args['page']));
        $this->out['add_href'] = $router->pathFor('AdminController:articleAction');
        $this->out['this_route'] = $router->pathFor('AdminController:articlesAction');
        foreach ($this->out['data'] as $id => $item) {
            $this->out['data'][$id]['b_edit'] = $router->pathFor('AdminController:articleAction', ['id' => $item['id']]);
            $this->out['data'][$id]['b_delete'] = $router->pathFor('AdminController:articleAction', ['id' => $item['id']]) . '?c=del';
        }

        session_write_close();
        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('admin/articles.html', $this->out));
    }

    public function articleAction($request, $response, $args)
    {
        if ($request->isPost())
            return $this->articleSaveAction($request, $response, $args);

        $cmd = $request->getQueryParam('c');
        $router = $this->ci->get('router');

        if (isset($args['id']) AND $cmd == 'del') {
            // delete article $args['id'], redirect to articlesAction
            global $_SESSION;
            if (session_status() == PHP_SESSION_NONE)
                session_start();
            $model = new AdminArticles($this->ci['db']);

            $_SESSION['message'] = $model->articleDelete($args['id']);

            session_write_close();
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor('AdminController:articlesAction'));
        }

        $this->preparer();
        $this->out['menu_active'] = $router->pathFor('AdminController:articlesAction');
        $model = new AdminArticles($this->ci['db']);

        if (isset($args['id'])) {
            // edit category $args['id']
            $this->out['data'] = $model->articleEdit($args['id']);
            $this->out['action'] = $this->ci->get('router')->pathFor('AdminController:articleAction', ['id' => $args['id']]);
        } else {
            // add category
            $this->out['data'] = $model->articleAdd();
            $this->out['action'] = $this->ci->get('router')->pathFor('AdminController:articleAction');
        }

        $this->out['cats'] = $model->getCategoriesPairs();

        session_write_close();
        return $this->ci['response']
            ->withHeader('Content-Type', 'text/html')
            ->write($this->ci->get('twig')->render('admin/article.html', $this->out));
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

        $model = new AdminIndex($this->ci['db']);
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
        global $_SESSION;

        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $router = $this->ci->get('router');
        $route = $request->getAttribute('route');

        $data = $request->getParsedBody();
        $required_fields = [
            'title' => 'text',
            'text' => 'html',
            'uri' => 'text',
            'meta_title' => 'text',
            'meta_description' => 'text',
            'meta_keywords' => 'text'
        ];

        $cleared_data = $this->testPostData($required_fields, $data);

        if ($cleared_data === false) {
            $_SESSION['message'] = 'Invalid data!';
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor($route->getName(),$args));
        }


        $model = new AdminCategories($this->ci['db']);
        $id = isset($args['id'])?intval($args['id']):null;
        $r = $model->saveCategory($id,$cleared_data);

        if ($r !== true) {
            $_SESSION['message'] = $r;
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor($route->getName(),$args));
        }else {
            $_SESSION['message'] = 'Saved.';
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor('AdminController:categoriesAction'));
        }
    }

    public function articleSaveAction($request, $response, $args)
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
            'uri' => 'text',
            'meta_title' => 'text',
            'meta_description' => 'text',
            'meta_keywords' => 'text',
            'parent'=>'int'
        ];

        $cleared_data = $this->testPostData($required_fields, $data);

        if ($cleared_data === false) {
            $_SESSION['message'] = 'Invalid data!';
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor($route->getName(),$args));
        }
        if($cleared_data['parent'] == 0)
            unset($cleared_data['parent']);

        $model = new AdminArticles($this->ci['db']);
        $id = isset($args['id'])?intval($args['id']):null;
        $r = $model->saveArticle($id,$cleared_data);

        if ($r !== true) {
            $_SESSION['message'] = $r;
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor($route->getName(),$args));
        }else {
            $_SESSION['message'] = 'Saved.';
            return $response
                ->withStatus(301)
                ->withHeader('Location', $this->ci['siteURI'] . $router->pathFor('AdminController:articlesAction'));
        }
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