<?php

class Application_Model_UserType extends Zend_Db_Table_Abstract
{

    protected $_name = 'user_type';

    public function getUserTypeList()
    {

        $select  = $this->_db->select()->from($this->_name);
        $result = $this->getAdapter()->fetchAll($select);
        return $result;

    }

}