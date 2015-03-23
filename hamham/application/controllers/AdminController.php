<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
        
        
    }
    
    public function categorylistAction()
    {
        echo '###';
        $category_model = new Application_Model_Category();
        //$this->view->categories = $category_model->listCategories();
        
        $this->_helper->json($category_model->listCategories());
        echo '###';
        //echo Zend_Json::prettyPrint($json, array("indent" => " "));
        
        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(TRUE);
    }


}

