<?php

class Application_Model_PrivateMessage extends Zend_Db_Table_Abstract
{
    protected $_name= 'private_message';
    
    function addPrivateMessage($data){
        $row = $this->createRow();
        $row->sendUserID = $data['sendUserID'];
        $row->receiveUserID = $data['receiveUserID'];
        $row->msg_body = $data['msg_body'];
        $row->msg_title = $data['msg_title'];
        //var_dump($row);
        //exit;
        return $row->save();        
    }
    function getPrivateMessageByRecieverID($id){
        $privateMessages = new Application_Model_PrivateMessage();
        $row = $privateMessages->fetchAll("receiveUserID = $id");
        return $row->toArray();
    }
    
    function getPrivateMessageBySenderID($id){
        $privateMessages = new Application_Model_PrivateMessage();
        $row = $privateMessages->fetchRow($privateMessages->select()->where('sendUserID = ?', $id));
        return $row->toArray();
    }

    
}
