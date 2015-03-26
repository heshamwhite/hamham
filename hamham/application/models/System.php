<?php

class Application_Model_System extends Zend_Db_Table_Abstract
{
    protected $_name = "system";

    function systemState(){
        $where[] = "id = system";
        return $this->fetchAll()->toArray();
        
    }
    
    
    function editSystem($data){
        return $this->update($data);
    }

}

