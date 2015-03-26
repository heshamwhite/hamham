<?php

class Application_Model_UserFacebook extends Zend_Db_Table_Abstract
{
    protected $_name= 'userFacebook';
    
    function addUserFacebook($data){
        
        
        // To check if the user already signed in with this facebook before
        // and not enter him the db twice
        $myUser = new Application_Model_UserFacebook();
        $row = $myUser->fetchRow($myUser->select()->where('facebookID = ?', $data['id']));
        //var_dump($row);
        
        if($row == NULL){
            $row = $this->createRow();
            //echo '<br/>'.$data['id'].'<br/>'; exit;
            $row->facebookID = $data['id'];
            $row->first_name = $data['first_name'];
            $row->last_name = $data['last_name'];
            $row->email = $data['email'];
            $row->gender = $data['gender'];
            $row->link = $data['link'];
            $row->locale = $data['locale'];
            $row->name = $data['name'];
            $row->updated_time = $data['updated_time'];
            $row->verified = $data['verified'];
            $row->fbImage = $data['profileimage'];
        }
        return $row->save();
    }


}

