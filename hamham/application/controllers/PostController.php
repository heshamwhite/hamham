<?php

class PostController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function lockAction()
    {
        $Post_id =  $this->_request->getParam("id") ;
        
        if($Post_id && ctype_digit($Post_id)){
            
                $auth = Zend_Auth::getInstance();
                $user_info = $auth->getIdentity();
                $user_info->id = "1";
                
                if ($user_info) {
                    
                    $PostModel = new Application_Model_Post();
                    $Current = $PostModel->getMainPostById($Post_id);
                    $data['id']=$Post_id;
                    if($Current[0]['locked']==1)
                    $data['locked']= "0";
                    else $data['locked']= "1";
                    $PostModel->editPost($data);
                    $this->_redirect("/Post/index/id/$Post_id"); 
                }
  
        }
           
           $this->_redirect('/Error/pagenotvalid/');
        
    }
    
    public function deleteAction()
    {
        $Post_id =  $this->_request->getParam("id") ;
        
        if($Post_id && ctype_digit($Post_id)){
            
                $auth = Zend_Auth::getInstance();
                $user_info = $auth->getIdentity();
                $user_info->id = "1";
                
                if ($user_info) {
                    
                    $PostModel = new Application_Model_Post();
                    $Current = $PostModel->getMainPostById($Post_id);
                    $data['id']=$Post_id;
                    if($Current[0]['deleted']==1)
                    $data['deleted']= "0";
                    else $data['deleted']= "1";
                    $PostModel->editPost($data);
                    $link = "/Forum/index/id/".$Current[0]['forum_id'];
                    $this->_redirect($link); 
                }
  
        }
           
           $this->_redirect('/Error/pagenotvalid/');
        
    }
    
    public function indexAction()
    {
         $Post_id =  $this->_request->getParam("id") ;
            if($Post_id && ctype_digit($Post_id)){
            $Post = new Application_Model_Post();
            $Current = $Post->getMainPostById($Post_id);
            
            if (!empty($Current)) {
                $this->view->Post = $Current[0];
               
                
                $Forum = new Application_Model_Forum();
                $myForum =  $Forum->getForumById($Current[0]['forum_id'])[0];
                $this->view->myForum = $myForum;
                
                if(!empty($myForum['parent_id'])){
                    
                    $myForumParent = $Forum->getForumById($myForum['parent_id'])[0];
                    $this->view->myForumParent = $myForumParent;
                }
                
                $SubPosts = $Post->getSubPostsByPostId($Post_id);
                if (!empty($SubPosts)) {
                    $this->view->SubPosts= $SubPosts;
                }
                               
            } else {
                $this->_redirect('/Error/pagenotvalid/');
            }
            
        }else {
                $this->_redirect('/Error/pagenotvalid/');
        }
    }

    public function addAction()
    {
        $addPostForm  = new Application_Form_AddPost();
        $forum = $this->_request->getParam("forum");
        //var_dump($this->_request->getParams()); 'valid';exit;


        if($addPostForm->isValid($this->_request->getParams())){
            
                $forumID= $this->_request->getParam('forumID');
                $post_info = $addPostForm->getValues();
                $post_model = new Application_Model_Post();
                $post_model->addPost($post_info);
                $this->redirect("/forum/index/id/$forumID");
                
            
        }elseif($forum){
            
            $auth = Zend_Auth::getInstance();
            $user_info = $auth->getIdentity();
            if (!$user_info) {
                //$this->redirect("user/login");
            }
            
            $addPostForm->getElement("forumID")->setValue("$forum");
            $addPostForm->getElement("user_id")->setValue("1");
            $addPostForm->getElement("postID")->setValue('');
            $this->view->addPostForm = $addPostForm;
            
        }elseif($this->_request->getParam('body')){
            echo 'here';exit;
        }else{
            $this->redirect("/eroor/error/");
        }
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

