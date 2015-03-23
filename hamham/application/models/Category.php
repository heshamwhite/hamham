<?php

class Application_Model_Category extends Zend_Db_Table_Abstract
{
    protected $_name = "category";

    function listCategories(){
        $where[] = "deleted = 0";
        return $this->fetchAll($where)->toArray();
        
    }
    
    function addCategory($data)
    {
        //$cat = new Category();
        $rowset   = $this->fetchAll("name = '$data'");
 
        $rowCount = count($rowset);
        if($rowCount>0)return false;
        $row = $this->createRow();
        $row->name = $data;
        $row->save();
        return true;
    }

    
    function editCategory($id,$name)
    {
        $row = $this->fetchRow($this->select()->where('id = ?', $id));
 
        // Change the value of one or more columns
        $row->name = "$name";

        // UPDATE the row in the database with new values
        return $row->save();
    }
    
    function deleteCategory($id){
        $data=array(
            'deleted'=>'1'
        );
        return $this->update($data, "id=$id");
        
    }
    
}

