<?php

class Application_Model_Post extends Zend_Db_Table_Abstract
{
    protected $_name = 'post';

    function getMainPostById($id){       
        $where[] = "id = $id";
        $where[] = "parent_id IS NULL";
        $where[] = "deleted = 0";
        return $this->fetchAll($where)->toArray();
    }
    
    
    function getSubPostsByPostId($Post_id){
        $where[] = "parent_id = $Post_id";
        $where[] = "deleted = 0";
        return $this->fetchAll($where)->toArray();    
    }
    
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


    function editPost($data){
        return $this->update($data, "id=".$data['id']);
    }

    function  addPost($data){
        $row = $this->createRow();
        $row->title = $data['title'];
        $row->body = $data['body'];
        $row->user_id = $data['user_id'];
        $row->date = date('Y-m-d H:i:s');
        $row->forum_id = $data['forumID']; 
        if($data['postID']){
            $row->parent_id = $data['postID'];
        }
        return $row->save();
    }
    
    function latestPost($forumID){
        
        return $this->fetchAll(
            $this->select()
                ->from($this, array(new Zend_Db_Expr('max(date)'), id))
            );
   
    }
    
    
}

