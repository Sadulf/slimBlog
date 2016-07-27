<?php

/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 26.07.2016
 * Time: 16:27
 */
class AdminIndex extends Admin
{

    private $_sqlGetIndexArticle = 'SELECT * FROM `articles` WHERE `type` = 3 LIMIT 1;';
    private $_sqlUpdateIndexArticle = 'UPDATE `articles` SET :values WHERE `type` = 3;';

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

}