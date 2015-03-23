<?php

class PostController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function stickyAction()
    {
        
        $Post_id =  $this->_request->getParam("id") ;
        
        if($Post_id && ctype_digit($Post_id)){
            
            $Post = new Application_Model_Post();
            $Current = $Post->getMainPostById($Post_id);
            
            if (!empty($Current[0])) {
               
                $data['id']= $Current[0]['id'];
                if($Current[0]['sticky'] == 1){$data['sticky']= 0; }
                else{$data['sticky']= 1; }
                $Post->editPost($data);
                $Forum_ID = $Current[0]['forum_id'];
                
                
                
                $this->redirect("/Forum/index/id/$Forum_ID");
            }else{
                $this->_helper->viewRenderer('Error/pageNotFound', null, true);
            }
    
    }else{
        $this->_helper->viewRenderer('Error/pageNotFound', null, true);
    }
    
    
    }    
    


}

