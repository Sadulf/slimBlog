<?php

/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 26.07.2016
 * Time: 16:27
 */
class Admin
{

    private $_pdo;
    private static $link = null;
    private $perpage = 30;

    private $_sqlGetIndexArticle = 'SELECT * FROM `articles` WHERE `type` = 3 LIMIT 1;';
    private $_sqlUpdateIndexArticle = 'UPDATE `articles` SET :values WHERE `type` = 3;';

    private $_sqlGetCategoriesCnt = 'SELECT COUNT(*) AS \'count\' FROM `articles` WHERE `type` = 2;';
    private $_sqlGetCategories = 'SELECT `id`,`title` FROM `articles` WHERE `type` = 2 LIMIT ?,?;';
    private $_sqlDeleteCategory = 'UPDATE `articles` SET `parent`=0 WHERE `parent`=?; DELETE FROM `articles` WHERE `id` = ? LIMIT 1;';
    private $_sqlGetCategory = 'SELECT * FROM `articles` WHERE `type` = 2 AND `id`=? LIMIT 1;';
    private $_sqlUpdateCategory = 'UPDATE `articles` SET :values WHERE `type` = 2 AND `id`=?;';
    private $_sqlInsertCategory = 'INSERT INTO `articles` SET :values';

    private $_sqlGetArticlesCnt = 'SELECT COUNT(*) AS \'count\' FROM `articles` WHERE `type` = 1;';
    private $_sqlGetArticlesCatCnt = 'SELECT COUNT(*) AS \'count\' FROM `articles` WHERE `type` = 1 AND `parent`=?;';
    private $_sqlGetArticles = 'SELECT `id`,`title` FROM `articles` WHERE `type` = 1 LIMIT ?,?;';
    private $_sqlGetArticlesCat = 'SELECT `id`,`title` FROM `articles` WHERE `type` = 1 AND `parent`=:cat LIMIT :l1,:l2;';
    private $_sqlGetArticlesCategories = 'SELECT `id`,`title` FROM `articles` WHERE `type` = 2;';
    private $_sqlDeleteArticle = 'DELETE FROM `articles` WHERE `id` = ? LIMIT 1;';
    private $_sqlGetArticle = 'SELECT * FROM `articles` WHERE `type` = 1 AND `id`=? LIMIT 1;';
    private $_sqlUpdateArticle = 'UPDATE `articles` SET :values WHERE `type` = 1 AND `id`=?;';
    private $_sqlInsertArticle = 'INSERT INTO `articles` SET :values';


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
     * Get article for index page
     */
    public function getIndex()
    {
        $stm = $this->_pdo->prepare($this->_sqlGetIndexArticle);
        $stm->execute();
        return $stm->fetch();
    }

    public function saveIndex($data)
    {
        $stm = $this->_pdo->prepare(str_replace(':values', $this->pdoSet($data), $this->_sqlUpdateIndexArticle));
        //echo $stm->interpolateQuery();die();
        return $stm->execute();
    }


    // CATEGORIES OPERATIONS

    public function getCategories($page = 0)
    {
        // get categories count
        $stm = $this->_pdo->prepare($this->_sqlGetCategoriesCnt);
        $stm->execute();
        $res['count'] = $stm->fetchColumn();
        if ($res['count'] === false)
            return $res;

        // calculate pages
        $res['pagination'] = $this->calcPages($page, $res['count']);

        // get categories
        $limit = [$res['pagination']['current'] * $this->perpage, $this->perpage];

        $stm = $this->_pdo->prepare($this->_sqlGetCategories);
        $stm->bindParam(1, $limit[0], PDO::PARAM_INT);
        $stm->bindParam(2, $limit[1], PDO::PARAM_INT);

        // $stm->interpolateQuery();die();

        $stm->execute();
        $res['data'] = $stm->fetchAll();
        return $res;
    }

    public function categoryDelete($id){
        $stm = $this->_pdo->prepare($this->_sqlDeleteCategory);
        $stm->bindParam(1, $id, PDO::PARAM_INT);
        $stm->bindParam(2, $id, PDO::PARAM_INT);
        if($stm->execute() === false)
            return 'Произошла ошибка при удалении категории';
        else
            return 'Категория удалена';
    }

    public function categoryEdit($id){
        $stm = $this->_pdo->prepare($this->_sqlGetCategory);
        $stm->bindParam(1, $id, PDO::PARAM_INT);
        $stm->execute();
        return  $stm->fetch();
    }

    public function categoryAdd(){
        return  [
            'uri'=>'',
            'title'=>'',
            'meta_title'=>'',
            'meta_description'=>'',
            'meta_keywords'=>'',
            'text'=>''
        ];
    }

    public function saveCategory($id,$data){
        $this->_pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        if(is_null($id)){
            $data['published'] = time();
            $data['type'] = 2;
            $data['parent'] = 0;

            $stm = $this->_pdo->prepare(str_replace(':values', $this->pdoSet($data), $this->_sqlInsertCategory));
            $r = $stm->execute();
        }else{
            $stm = $this->_pdo->prepare(str_replace(':values', $this->pdoSet($data), $this->_sqlUpdateCategory));
            $stm->bindParam(1, $id, PDO::PARAM_INT);
            $r = $stm->execute();
        }

        if($r === false){
            return $stm->errorInfo()[2];
        }else{
            return true;
        }

    }



    // Articles OPERATIONS

    public function getArticles($category=null, $page = 0)
    {
        // get articles count
        if(is_null($category)) {
            $stm = $this->_pdo->prepare($this->_sqlGetArticlesCnt);
        }else{
            $stm = $this->_pdo->prepare($this->_sqlGetArticlesCatCnt);
            $stm->bindParam(1, $category, PDO::PARAM_INT);
        }
        $stm->execute();
        $res['count'] = $stm->fetchColumn();
        if ($res['count'] === false)
            return $res;

        // calculate pages
        $res['pagination'] = $this->calcPages($page, $res['count']);

        // get articles
        $limit = [$res['pagination']['current'] * $this->perpage, $this->perpage];

        if(is_null($category)) {
            $stm = $this->_pdo->prepare($this->_sqlGetArticlesCat);
            $stm->bindParam(':cat', $limit[0], PDO::PARAM_INT);
        }else{
            $stm = $this->_pdo->prepare($this->_sqlGetArticles);
        }

        $stm->bindParam(':l1', $limit[0], PDO::PARAM_INT);
        $stm->bindParam(':l2', $limit[1], PDO::PARAM_INT);

        // $stm->interpolateQuery();die();

        $stm->execute();
        $res['data'] = $stm->fetchAll();

        // get categories list
        $stm = $this->_pdo->prepare($this->_sqlGetArticlesCategories);
        $stm->execute();
        $res['cats'] = $stm->fetchAll(PDO::FETCH_KEY_PAIR);

        return $res;
    }

    public function articleDelete($id){
        $stm = $this->_pdo->prepare($this->_sqlDeleteCategory);
        $stm->bindParam(1, $id, PDO::PARAM_INT);
        $stm->bindParam(2, $id, PDO::PARAM_INT);
        if($stm->execute() === false)
            return 'Произошла ошибка при удалении категории';
        else
            return 'Категория удалена';
    }

    public function articleEdit($id){
        $stm = $this->_pdo->prepare($this->_sqlGetCategory);
        $stm->bindParam(1, $id, PDO::PARAM_INT);
        $stm->execute();
        return  $stm->fetch();
    }

    public function articleAdd(){
        return  [
            'uri'=>'',
            'title'=>'',
            'meta_title'=>'',
            'meta_description'=>'',
            'meta_keywords'=>'',
            'text'=>''
        ];
    }

    public function saveArticle($id,$data){
        $this->_pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        if(is_null($id)){
            $data['published'] = time();
            $data['type'] = 2;
            $data['parent'] = 0;

            $stm = $this->_pdo->prepare(str_replace(':values', $this->pdoSet($data), $this->_sqlInsertCategory));
            $r = $stm->execute();
        }else{
            $stm = $this->_pdo->prepare(str_replace(':values', $this->pdoSet($data), $this->_sqlUpdateCategory));
            $stm->bindParam(1, $id, PDO::PARAM_INT);
            $r = $stm->execute();
        }

        if($r === false){
            return $stm->errorInfo()[2];
        }else{
            return true;
        }

    }

    /** Makes SET part of sql request. All data MUST be tested and prepared before!
     * @param $data array   array of values
     * @return string   SET part of SQL request
     */
    private function pdoSet($data)
    {
        $res = [];
        foreach ($data as $name => $value) {
            if (is_numeric($value))
                $res[] = '`' . $name . '` = ' . $value;
            else if (is_bool($value))
                $res[] = '`' . $name . '` = ' . ($value ? 'true' : 'false');
            else if (is_array($value))
                $res[] = '`' . $name . '` = ' . $this->_pdo->quote(serialize($value));
            else // string
                $res[] = '`' . $name . '` = ' . $this->_pdo->quote($value);
        }
        return implode(', ', $res);
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