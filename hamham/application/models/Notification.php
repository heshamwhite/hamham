<?php

class Application_Model_Notification extends Zend_Db_Table_Abstract
{
    protected $_name = "notification";
    
    function addNotification($data){
        $row = $this->createRow();
        $row->userID = $data['userID'];
        $row->notificationTime = $data['notificationTime'];
        $row->notificationBody = $data['notificationBody'];
        $row->type = $data['type'];
        return $row->save();
    }
    function getAllUnseenNotificationByUserID($userID){
        $where[] = "seen = 0 AND userID = $userID";
        return $this->fetchAll($where)->toArray();  
    }
    function setNotificationAsSeen($notificationID){
        $row = $this->fetchRow($this->select()->where('id = ?', $notificationID));
        $row->seen = 1;
        return $row->save();
    }
}

