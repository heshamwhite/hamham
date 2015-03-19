<?php

class Application_Model_Forum extends Zend_Db_Table_Abstract
{

    protected $_name = 'forum';
    
    function addForum($data){
        $row = $this->createRow();
        $row->name = $data['name'];
        $row->category_id = $data['category_id'];
        if (isset($row->parent_id)) {
            $row->parent_id = $data['parent_id'];
        }
        if (isset($row->locked)) {
            $row->locked = $data['locked'];
        }
        return $row->save();
    }
    
    function listForums(){
        
        return $this->fetchAll()->toArray();
    }
    
    function deleteForum($id){
        return $this->delete("id=$id");
    }
    
    function editForum($data){
        return $this->update($data, "id=".$data['id']);
    
    }
    
    function getForumById($id){
        return $this->find($id)->toArray();
    }
    
    function getSubForumsById($id){
        $where[] = "parent_id = $id";
        return $this->fetchAll($where)->toArray();
    }    
    

}

