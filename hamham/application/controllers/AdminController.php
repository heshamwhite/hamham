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
    
    public function listSearch($list,$item)
    {
        $ret = -1;
        $list[]=5;
        for($i=0;$i<sizeof($list);$i++)
        {
            if($list[$i]['id']==$item['id'])return $i;    
        }
        return $ret;
    }
    
    public function editforumAction()
    {
        echo '###';
        $forum_model = new Application_Model_Forum();
        //$this->view->categories = $category_model->listCategories();
        //var_dump($this->_request->getParams());
        $data = array(
            'id'=>$this->_request->getParam('id'),
        );
        $lock = $this->_request->getParam('locked') ;
        if(isset($lock))
        {
            $data['locked']= $lock;
            if($lock==1)
            {
                $forum_model->editSubForums($this->_request->getParam('id'),$data);
            }
        }
        $deleted = $this->_request->getParam('deleted');
        if(isset($deleted))
        {
            $data['deleted']= $deleted;
        }
        $name = $this->_request->getParam('name') ;
        if(isset($name))
        {
            $data['name']= $name;
        }
        
        $this->_helper->json($forum_model->editForum($data));
        echo '###';
        //
        //var_dump($this->_request->getParams());
        //$this->_helper->json(array("validity"=>)));
    }
    
    public function forumlistAction()
    {
        echo '###';
        $category_model = new Application_Model_Category();
        $forum_model = new Application_Model_Forum();
        $category_list = $category_model->listCategories();
        $lists = array();
        for ($i=0;$i<sizeof($category_list);$i++)
        {
            $lists[$i] = $forum_model->getSubForumsByCategoryId($category_list[$i]['id']);
            
            for($j=0;$j<sizeof($lists[$i]);$j++)
            {
                $lists[$i][$j]['disp'] = $lists[$i][$j]['name'];
            }
            
            $category_list[$i]['forums'] = $lists[$i];
            
            for($j=0;$j<sizeof($lists[$i]);$j++)
            {
                $lists[$i][$j]['disp'] = $lists[$i][$j]['name'];
                $temp = $forum_model->getSubForumsById($lists[$i][$j]['id']) ;
                for($x=0;$x<sizeof($temp);$x++)
                {
                    $temp[$x]['disp']=$lists[$i][$j]['disp']." / ".$temp[$x]['name'];
                }
                $key = $this->listSearch($category_list[$i]['forums'],$lists[$i][$j]); 
                array_splice( $category_list[$i]['forums'] , $key+1,0,$temp  );
            }
            
            //for($j=0;$j<sizeof($lists[$i]);$j++)
            //{
           //     $lists[$i][$j]['disp'] = $lists[$i][$j]['name'];
           // }
        
            
                    
        }
        //$category_list[0]['forums']=$forum_model->getSubForumsByCategoryId(1);
        $this->_helper->json($category_list);
        echo '###';
       
        
        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(TRUE);
    }
    
    
    public function userlistAction()
    {
        echo '###';
        $user_model = new Application_Model_User();
        //$this->view->categories = $category_model->listCategories();
        
        $this->_helper->json($user_model->listUsers());
        echo '###';
        //echo Zend_Json::prettyPrint($json, array("indent" => " "));
        
        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(TRUE);
    }
    
    public function edituserAction()
    {
        echo '###';
        $user_model = new Application_Model_User();
        //$this->view->categories = $category_model->listCategories();
        
        $data = array(
            'id'=>$this->_request->getParam('id'),
        );
        $lock = $this->_request->getParam('firstname') ;
        if(isset($lock))
        {
            $data['firstname']= $lock;
        }
        $lastname = $this->_request->getParam('lastname');
        if(isset($lastname))
        {
            $data['lastname']= $lastname;
        }
        $username = $this->_request->getParam('username') ;
        if(isset($username))
        {
            $data['username']= $username;
        }
        $email = $this->_request->getParam('email') ;
        if(isset($email))
        {
            $data['email']= $email;
        }
        $user_type_id = $this->_request->getParam('user_type_id') ;
        if(isset($user_type_id))
        {
            $data['user_type_id']= $user_type_id;
        }
        $birth_date = $this->_request->getParam('birth_date') ;
        if(isset($birth_date))
        {
            $data['birth_date']= $birth_date;
        }
        $gender = $this->_request->getParam('gender') ;
        if(isset($gender))
        {
            $data['gender']= $gender;
        }
        $country = $this->_request->getParam('country') ;
        if(isset($country))
        {
            $data['country']= $country;
        }
        //
        
        $id= $this->_request->getParam('id');
        
        $this->_helper->json(array("validity"=>$user_model->updateUserByID($id,$data)));
    
        //echo '###';
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

    public function categoryaddAction()
    {
        echo '###';
        $category_model = new Application_Model_Category();
        $name= $this->_request->getParam('name');
        //$val = ()?true:false;
        $this->_helper->json(array("validity"=>$category_model->addCategory($name)));
    }
    
    public function forumaddAction()
    {
        echo '###';
        $forum_model = new Application_Model_Forum();
        //$name= $this->_request->getParam('name');
        //$val = ()?true:false;
        $this->_helper->json(array("validity"=>$forum_model->addForum($this->_request->getParams())));
    }
    
    public function categoryeditAction()
    {
        echo '###';
        $category_model = new Application_Model_Category();
        $name= $this->_request->getParam('name');
        $id= $this->_request->getParam('id');
        //$val = ()?true:false;
        $this->_helper->json(array("validity"=>$category_model->editCategory($id,$name)));
    }
    
    
    
    

    public function categorydeleteAction()
    {
        echo '###';
        $category_model = new Application_Model_Category();
        $id= $this->_request->getParam('id');
        
        $this->_helper->json(array("validity"=>$category_model->deleteCategory($id)));
    }
}

