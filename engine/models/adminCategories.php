<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 27.07.2016
 * Time: 15:58
 */

class AdminCategories extends Admin
{
    private $_sqlGetCategoriesCnt = 'SELECT COUNT(*) AS \'count\' FROM `articles` WHERE `type` = 2;';
    private $_sqlGetCategories = 'SELECT `id`,`title` FROM `articles` WHERE `type` = 2 LIMIT ?,?;';
    private $_sqlDeleteCategory = 'UPDATE `articles` SET `parent`=0 WHERE `parent`=?; DELETE FROM `articles` WHERE `id` = ? LIMIT 1;';
    private $_sqlGetCategory = 'SELECT * FROM `articles` WHERE `type` = 2 AND `id`=? LIMIT 1;';
    private $_sqlUpdateCategory = 'UPDATE `articles` SET :values WHERE `type` = 2 AND `id`=?;';
    private $_sqlInsertCategory = 'INSERT INTO `articles` SET :values';


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

}