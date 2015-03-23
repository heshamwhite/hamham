<?php

class Application_Model_User extends Zend_Db_Table_Abstract
{
    protected $_name= 'user';
    
    function addUser($data){
        $row = $this->createRow();
        $row->username = $data['username'];
        $row->firstname = $data['firstname'];
        $row->lastname = $data['lastname'];
        $row->email = $data['email'];
        $row->password = md5($data['password']);
        $row->user_type_id = $data['usertype'];
        $row->gender = $data['gender'];
        $row->country = $data['country'];
        $row->profileimage = "NOFORNOW";
        
        $year =  (idate('Y') - 100) + $data['year'];
        $month = $data['month'] + 1;
        $day= $data['day'] + 1;
        $hour = 0;
        $minute =0;
        $mydob =strtotime("$year-$month-$day $hour:$minute");
        echo "$year-$month-$day $hour:$minute  %%%";
        echo $mydob;
        $row->birth_date = "$year-$month-$day $hour:$minute";
        return $row->save();
    }
    function getUserByID($id){
        $myUser = new Application_Model_User();
        $row = $myUser->fetchRow($myUser->select()->where('id = ?', $id));
        //var_dump($row);
        return $row->toArray();
    }
    function updateUserByID($id,$data){
        if(!empty($data['password']))
            $data['password']=md5($data['password']);
        else
            unset ($data['password']);
        $this->update($data, "id=".$id);
        return $this->fetchAll()->toArray();
    }
    function updateUserByUsername($username,$data){
        $this->update($data, "username='".$username."'");
        return $this->fetchAll()->toArray();
    }
    
    function listUsers(){
        $where[] = "deleted = 0";
        return $this->fetchAll($where)->toArray();  
    }
}

