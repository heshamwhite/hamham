<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
     
        $CategoriesModel = new Application_Model_Category();
        $Categories =$CategoriesModel->listCategories();
        $this->view->Categories = $Categories;
        
    }


}
