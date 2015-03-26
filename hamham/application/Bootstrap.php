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
//        if(!$authorization->hasIdentity()) {
//             echo 'Hello, Guest';
//            //echo "Error";
//           //$this->redirect("Auth/index"); 
//             $frontCtrl = Zend_Controller_Front::getInstance();
//             //$frontCtrl->redirect("Auth");
//             $view->logRed = 1;
//            
//            //PostController::goHere();
//        }
//        else{
//            echo "hello ";
//            echo ($authorization->getIdentity()->username);
//            echo "<a href='/hamham/hamham/public/User/logout'> Logout </a>";
//            
//            //$view->myheader = "hello ".($authorization->getIdentity()->email)."<a href='fsds.php'> Logout </a>";
//            
//         }
         
        if(isset($_COOKIE['hamham'])){
            
        } 
        if(!$authorization->hasIdentity()) {
            $frontCtrl = Zend_Controller_Front::getInstance();
             $view->logRed = 1;
        }
         
    }
//    protected function _initDoctype()
//    {
//        $this->bootstrap('view');
//        $view = $this->getResource('view');
//        $view->doctype('XHTML1_STRICT');
//    }
    

 /**
     * 
     * This puts the application.ini setting in the registry
     */
    protected function _initConfig()
    {
        Zend_Registry::set('config', $this->getOptions());
    }

    /**
     * 
     * This function initializes routes so that http://host_name/login
     * and http://host_name/logout is redirected to the user controller.
     * 
     * There is also a dynamic route for clean callback urls for the login 
     * providers
     */
    protected function _initRoutes()
    {
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();

        $route = new Zend_Controller_Router_Route('login/:provider',
                                                  array(
                                                  'controller' => 'user',
                                                  'action' => 'login'
                                                  ));
        $router->addRoute('login/:provider', $route);

        $route = new Zend_Controller_Router_Route_Static('login',
                                                         array(
                                                         'controller' => 'user',
                                                         'action' => 'login'
                                                         ));
        $router->addRoute('login', $route);

        $route = new Zend_Controller_Router_Route_Static('logout',
                                                         array(
                                                         'controller' => 'user',
                                                         'action' => 'logout'
                                                         ));
        $router->addRoute('logout', $route);
    }
}

