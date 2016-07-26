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

    public function saveIndex($data)
    {
        $stm = $this->_pdo->prepare(str_replace(':values', $this->pdoSet($data), $this->_sqlUpdateIndexArticle));
        //echo $stm->interpolateQuery();die();
        return $stm->execute();
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