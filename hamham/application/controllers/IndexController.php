<?php

class ForumController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
     
                
        // forum/1
        //check paramter Forum exist 
        //and it's value is number 
        // and it's value is an exit Forum id 
    $Forum_id =  $this->_request->getParam("id") ;
    
    if($Forum_id && ctype_digit($Forum_id)){
            
            $Forum = new Application_Model_Forum();
            $Current = $Forum->getForumById($Forum_id);
            if (!empty($Current)) {
                $this->view->Forum = $Current[0];
               
                if(!empty($Current[0]['parent_id'])){
                    
                    $Parent = $Forum->getForumById($Current[0]['parent_id']);
                    $this->view->Parent = $Parent[0];
                }
                
                //get sub forums
                $SubForums = $Forum->getSubForumsById($Forum_id);
                if (!empty($SubForums)) {
                    $this->view->SubForums = $SubForums;
                }
               
                 $Post = new Application_Model_Post();
                //get  sticky forum's posts
                $StickyPosts = $Post->getStickyPostsByForumsId($Forum_id);
                if (!empty($SubForums)) {
                    $this->view->StickyPosts = $StickyPosts;
                }
                
               //get  not sticky forum's posts
                $NotStickyPosts = $Post->getNotStickyPostsByForumsId($Forum_id);
                if (!empty($SubForums)) {
                    $this->view->NotStickyPosts = $NotStickyPosts;
                }
                
                
                               
            } else {
                $this->_helper->viewRenderer('Error/pageNotFound', null, true);
            }
            
        }else {
                $this->_helper->viewRenderer('Error/pageNotFound', null, true);
        }

    }


}
