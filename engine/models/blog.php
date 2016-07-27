<?php

class Blog
{

    private $_pdo;
    private static $link = null;
    private $perpage = 10;
    private $_sqlGetArticle = 'SELECT * FROM `articles` WHERE `uri` = ? AND `type` != 2 LIMIT 1;';

    private $_sqlGetCategory = "SELECT *, (SELECT COUNT(*) FROM `articles` b WHERE b.`type` = 1 AND b.`parent` = a.`id`) AS 'posts_count' FROM `articles` as a WHERE `uri` = ? AND `type` = 2 LIMIT 1;";
    private $_sqlGetCategoryPosts =
        "SELECT `uri`, `title`, CONCAT(TRIM(LEFT(`text`,200)),'...') AS `text`"
        . "FROM `articles` WHERE `type` = 1 AND `parent` = ? LIMIT ?,?;";

    private $_sqlGetIndexArticle = 'SELECT * FROM `articles` WHERE `type` = 3 LIMIT 1;';

    private $_sqlGetMenu = 'SELECT CONCAT(\'/\',`uri`,\'/\') as \'uri\', `title` FROM `articles` WHERE `type` = 2';

    /**
     * Constructor
     */
    public function __construct($_pdo)
    {
        if (!is_null(self::$link))
            return self::$link;

        self::$link = $this;
        $this->_pdo = $_pdo;
    }

    /**
     * Get menu for site
     */
    public function getMenu()
    {
        $stm = $this->_pdo->prepare($this->_sqlGetMenu);
        $stm->setFetchMode(PDO::FETCH_NUM);
        $stm->execute();
        $res = $stm->fetchAll();
        if (!is_array($res))
            $res = [];
        array_unshift($res, ['/', 'Home']);
        return $res;
    }

    /**
     * Get article by uri string
     */
    public function getArticle($uri)
    {
        $stm = $this->_pdo->prepare($this->_sqlGetArticle);
        $stm->bindParam(1, $uri, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetch();
    }

    /**
     * Get article for index page
     */
    public function getIndex()
    {
        $stm = $this->_pdo->prepare($this->_sqlGetIndexArticle);
        $stm->execute();
        return $stm->fetch();
    }

    /**
     * Get category by uri string
     */
    public function getCategory($uri, $page = 0)
    {

        // get general category data
        $stm = $this->_pdo->prepare($this->_sqlGetCategory);
        $stm->bindParam(1, $uri, PDO::PARAM_STR);
        $stm->execute();
        $res = $stm->fetch();
        if (!is_array($res))
            return $res;

        // calculate pages
        $res['pagination'] = $this->calcPages($page, $res['posts_count']);

        // get posts
        $limit = [$res['pagination']['current'] * $this->perpage, $this->perpage];

        $stm = $this->_pdo->prepare($this->_sqlGetCategoryPosts);
        $stm->bindParam(1, $res['id'], PDO::PARAM_INT);
        $stm->bindParam(2, $limit[0], PDO::PARAM_INT);
        $stm->bindParam(3, $limit[1], PDO::PARAM_INT);

        // echo $stm->interpolateQuery();die();

        $stm->execute();
        $res['posts'] = $stm->fetchAll();
        foreach ($res['posts'] as $k => $v) {
            $res['posts'][$k]['text'] = strip_tags($res['posts'][$k]['text']);
        }

        return $res;
    }

    private function calcPages($current, $count)
    {
        $max_page = ceil($count / $this->perpage) - 1;
        if ($current < 0)
            $current = 0;
        elseif ($current > $max_page)
            $current = $max_page;
        $pages = [];

        if ($max_page > 0)
            for ($i = 0; $i <= $max_page; $i++)
                $pages[$i] = $i + 1;

        return [
            'pages' => $pages,
            'current' => $current,
            'max' => $max_page,
            'count' => $count
        ];
    }
}