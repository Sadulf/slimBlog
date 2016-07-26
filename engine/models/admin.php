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