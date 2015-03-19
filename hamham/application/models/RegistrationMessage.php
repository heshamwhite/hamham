<?php

class Application_Model_RegistrationMessage extends Zend_Db_Table_Abstract
{
    protected $_name= 'registration_msg';
    
    function addMessage($data){
        $row = $this->createRow();
        $row->confirmationNumber = $data['confirmationNumber'];
        $row->username = $data['username'];
        return $row->save();
    }
    function getMessageByUsername($username){
        $myMsg= new Application_Model_RegistrationMessage();
        $row = $myMsg->fetchRow($myMsg->select()->where('username = ?', $username));
        //var_dump($row);
        return $row->toArray();
    }

}

