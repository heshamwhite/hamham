<?php

class Application_Model_Cookietoken extends Zend_Db_Table_Abstract
{
    protected $_name = "cookietoken";
    function addCookieToken($data){
        $row = $this->createRow();
        $row->username = $data['username'];
        $row->token = $data['token'];
        $row->cookieDate = $data['cookieDate'];
        return $row->save();
    }
    function getCookieTokenByUsername($username){
        $cookieToken = new Application_Model_Cookietoken();
        $row = $cookieToken->fetchRow($cookieToken->select()->where('username = ?', $username)->order('cookieDate'));
        return $row->toArray();
    }
    function deleteCookieTokenByUsername($username){
        $cookieToken = new Application_Model_Cookietoken();
        $row = $cookieToken->fetchRow($cookieToken->select()->where('username = ?', $username)->order('cookieDate'));
        $row->delete();
    }
    
    
}

