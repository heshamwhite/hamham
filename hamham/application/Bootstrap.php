<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initSession(){
        
        Zend_Session::start();
        $session = new Zend_Session_Namespace( 'Zend_Auth' );
        $session->setExpirationSeconds( 1800 );
    }
        protected function _initPlaceholders()
    {

        $this->bootstrap('View');
        $view = $this->getResource('View');
        $view->doctype('XHTML1_STRICT');
        //Meta
        $view->headMeta()->appendName('keywords', 'framework, PHP')
             ->appendHttpEquiv('Content-Type',
        'text/html;charset=utf-8');
        
        // Set the initial title and separator:
        $view->headTitle('OS Site')->setSeparator(' :: ');
        // Set the initial stylesheet:
        $view->headLink()->prependStylesheet('/css/site.css');
        // Set the initial JS to load:
        $view->headScript()->prependFile('/js/site.js');
        
        $authorization = Zend_Auth::getInstance();
        if(!$authorization->hasIdentity()) {
             echo 'Hello, Guest';
            //echo "Error";
           // $this->redirect("Auth/index"); 
             $frontCtrl = Zend_Controller_Front::getInstance();
             //$frontCtrl->redirect("Auth");
             $view->logRed = 1;
            
            //PostController::goHere();
        }
        else{
            echo "hello ";
            echo ($authorization->getIdentity()->username);
            echo "<a href='/hamham/hamham/public/User/logout'> Logout </a>";
            
            //$view->myheader = "hello ".($authorization->getIdentity()->email)."<a href='fsds.php'> Logout </a>";
            
         }
        
    }
//    protected function _initDoctype()
//    {
//        $this->bootstrap('view');
//        $view = $this->getResource('view');
//        $view->doctype('XHTML1_STRICT');
//    }
    


}

