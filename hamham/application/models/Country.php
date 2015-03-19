<?php

class Application_Model_Country extends Zend_Db_Table_Abstract

{

    protected $_name = 'country';

    public function getCountriesList()

    {

        $select  = $this->_db->select()->from($this->_name);
//        var_dump($select);
        $result = $this->getAdapter()->fetchAll($select);
//        var_dump($result);
        return $result;

    }

}