<?php

class Application_Model_Post extends Zend_Db_Table_Abstract
{
    protected $_name = 'post';

    function getNotStickyPostsByForumsId($ForumId){
        $where[] = "forum_id = $ForumId";
        $where[] = "sticky = 0";
        $where[] = "parent_id IS NULL";
        $where[] = "deleted = 0";
        return $this->fetchAll($where)->toArray();
    }

    function getStickyPostsByForumsId($ForumId){
        $where[] = "forum_id = $ForumId";
        $where[] = "sticky = 1";
        $where[] = "parent_id IS NULL";
        $where[] = "deleted = 0";
        return $this->fetchAll($where)->toArray();
    }

    function getMainPostById($PostId){
        $where[] = "id = $PostId";
        $where[] = "parent_id IS NULL";
        $where[] = "deleted = 0";
        //return $this->fetchAll($where)->toArray();
        return $this->find($PostId)->toArray();
    }
    
    function editPost($data){
        return $this->update($data, "id=".$data['id']);
    
    }
    
    
}

