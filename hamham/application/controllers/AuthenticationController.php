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
    public function login2Action()
    {
        $auth = TBS\Auth::getInstance();

        $providers = $auth->getIdentity();
        echo 'xxx';
        // Here the response of the providers are registered
        if ($this->_hasParam('provider')) {
           
            $provider = $this->_getParam('provider');
            if ($this->_hasParam('code')) {
                $adapter = new TBS\Auth\Adapter\Facebook(
                        $this->_getParam('code'));
                //var_dump($this->_getParam('code'));
                //var_dump($this->_getParam('email'));
                $result = $auth->authenticate($adapter);
                
                
                
                
                
                $thisUserFB = $auth->getIdentity();
                foreach ($thisUserFB as $provider){
                    var_dump($provider->getApi()->getProfile());
                }
                
                
                
                
                
                //exit;
           //     var_dump($this->_getParam('email'));
            }
            if($this->_hasParam('error')) {
                throw new Zend_Controller_Action_Exception('Facebook login failed, response is: ' . 
                    $this->_getParam('error'));
            }

            
            // What to do when invalid
            if (isset($result) && !$result->isValid()) {
                $auth->clearIdentity($this->_getParam('provider'));
                throw new Zend_Controller_Action_Exception('Login failed');
            } else {
                $this->_redirect('/Authentication/connect');
            }
        } else { // Normal login page
            $this->view->facebookAuthUrl = TBS\Auth\Adapter\Facebook::getAuthorizationUrl();

        }

    }
    public function connectAction()
    {
        $auth = TBS\Auth::getInstance();
        if (!$auth->hasIdentity()) {
            throw new Zend_Controller_Action_Exception('Not logged in!', 404);
        }
        $this->view->providers = $auth->getIdentity();
    }

    public function logoutAction()
    {
        \TBS\Auth::getInstance()->clearIdentity();
        //$this->_redirect('/');
    }
    


}

