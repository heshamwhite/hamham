<?php

class AuthenticationController extends Zend_Controller_Action
{

    public function init()
    {
        if(!$this->view->logRed){
            $this->redirect("User/profilepage"); 
        }
    }

    public function indexAction()
    {
       $form  = new Application_Form_Login();
       
       if($this->_request->isPost()){
          
           if($form->isValid($this->_request->getParams())){
             $username= $this->_request->getParam('username');
             $password= $this->_request->getParam('password');
             
               
                // get the default db adapter
                $db = Zend_Db_Table::getDefaultAdapter();
                
                //create the auth adapter
                $authAdapter = new Zend_Auth_Adapter_DbTable($db,'user','username', 'password');
                //set the email and password
                $authAdapter->setIdentity($username);
                $authAdapter->setCredential(md5($password));
                //$authAdapter ->setCredentialColumn(md5);
                
                //authenticate
                $result = $authAdapter->authenticate();
                //var_dump($result);
               // echo "hello".$result;
                if ($result->isValid()) {             
                    
                    $auth = Zend_Auth::getInstance();
                    
                    $storage = $auth->getStorage();
                    
                    $storage->write($authAdapter->getResultRowObject(array('email' , 'id' , 'username')));
                    echo "allright";
                    //$this->redirect("Post/list");
                    $this->redirect("User/profilepage");
                }else{
                    $this->view->form = $form;
                    echo "Username/Password is incorrect";
                }
           }
       }
       
	$this->view->form = $form;
    }


}

