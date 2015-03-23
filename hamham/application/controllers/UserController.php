<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        
       
    }
    

    public function bannAction()
    {
        $User_id =  $this->_request->getParam("id") ;
        
        if($User_id && ctype_digit($User_id)){
            
                $auth = Zend_Auth::getInstance();
                $user_info = $auth->getIdentity();
                $user_info->id = "1";
                
                if ($user_info) {
                    $UserModel = new Application_Model_User();
                    $Current = $UserModel->getUserByID($User_id);
                    $data['id']=$User_id;
                    $date = date('Y-m-d', strtotime(date().' + 7 days'));
                    $data['banned']= $date;
                    $UserModel->editUser($data);
                    $this->_redirect("/Index/index/"); 
                }
        }
           
           $this->_redirect('/Error/pagenotvalid/');
        
    }
    
    public function registerAction()
    {
        if(!$this->view->logRed){
            $this->redirect("User/profilepage"); 
        }
        // action body
        $form  = new Application_Form_Registration();
       
        if($this->_request->isPost()){
            if($form->isValid($this->_request->getParams())){
                
                /* Uploading Document File on Server */
                $upload = new Zend_File_Transfer_Adapter_Http();
                
                //var_dump($upload);
// 
                $upload->setDestination(APPLICATION_PATH . '/images');
               
                try {
                // upload received file(s)
                    $upload->receive();
                } catch (Zend_File_Transfer_Exception $e) {
                    $e->getMessage();
                }

                // so, Finally lets See the Data that we received on Form Submit
                $uploadedData = $form->getValues();
                //Zend_Debug::dump($uploadedData, 'Form Data:');

                // you MUST use following functions for knowing about uploaded file 
                # Returns the file name for 'p' named file element
                $name = $upload->getFileName('picture');
               
                # Returns the size for 'doc_path' named file element 
                # Switches of the SI notation to return plain numbers
                //$upload->setOption(array('useByteString' => false));
                $size = $upload->getFileSize('picture');

                # Returns the mimetype for the 'picture' form element
                $mimeType = $upload->getMimeType('picture');
 
                // following lines are just for being sure that we got data
                /*   print "Name of uploaded file: $name 
               ";
                print "File Size: $size 
               ";
                print "File's Mime Type: $mimeType";
                */
                // New Code For Zend Framework :: Rename Uploaded File
                $renameFile = 'newName.jpg';

                $fullFilePath = '../images/'.$renameFile;

                // Rename uploaded file using Zend Framework
                $filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));
                
                $ret = $filterFileRename -> filter(APPLICATION_PATH . '/images/'.'asd.jpg');
               
               
                $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $cid= substr(implode('', $seed), 1, 51);
                $user_info = $form->getValues();
                $imageName = $user_info['username'].uniqid();
                rename($name, APPLICATION_PATH . '/../public/images/users/'.$imageName); 
                $user_info['profileimage'] = 'images/users/'.$imageName;
                $user_model = new Application_Model_User();
                $user_model->addUser($user_info);
                $regiData['username'] = $user_info['username'];
                $regiData['confirmationNumber'] = $cid;
               
                $RegiMsg_model = new Application_Model_RegistrationMessage();
                $RegiMsg_model->addMessage($regiData);
               
                //$form->reset();       
                require_once 'Zend/Mail/Transport/Smtp.php';

                //Initialize needed variables
                $your_name = 'Hesham Admin';
                $your_email = 'hamhamkitchen@gmail.com'; //Or your_email@gmail.com for Gmail
                $your_password = 'chen123chen';
                $send_to_name = $user_info['username'];
                $send_to_email = $user_info['email'];
                
                //SMTP server configuration
                $smtpHost = 'smtp.gmail.com';
                $smtpConf = array(
                 'auth' => 'login',
                 'ssl' => 'ssl',
                 'port' => '465',
                 'username' => $your_email,
                 'password' => $your_password
                );
                $transport = new Zend_Mail_Transport_Smtp($smtpHost, $smtpConf);
                //echo "%$send_to_email%";
                
                //Create email
                $mail = new Zend_Mail();
                $mail->setFrom($your_email, $your_name);
                $mail->addTo($send_to_email, $send_to_name);
                $mail->setReplyTo('hamhamkitchen@gmail.com', 'Hamham');
                $mail->setSubject('Hello World');
                $mail->setBodyText("This is the body text of the email. Welcome to our website thanks for using Hamham Kitchen "
                        . "http://localhost/hamham/hamham/public/user/confirm/username/$send_to_name/cid/$cid Click Here to complete the registration "); 
                
                //Send
                $sent = true;
                try {
                 $mail->send($transport);
                }
                catch (Exception $e) {
                 $sent = false;
                }
                 $this->redirect("user/message/msg/regisucc");
           }
           else{
               echo "Your Information is not correct";
               //var_dump($this->_request->getParams());
           }
        }
       
        $this->view->form = $form;
        
    }

    public function editprofileAction()
    {
        // action body
        
        $authorization = Zend_Auth::getInstance();
        if(isset($authorization->getIdentity()->id)){
            $userid = $authorization->getIdentity()->id;    
            //$userid = $this->_request->getParam("userid");
            $form  = new Application_Form_Edit();
            $thisUser = new Application_Model_User();
            $data = $thisUser->getUserByID($userid);
            $birthDatesArr = explode('-', $data['birth_date']);
            $data['day'] = $birthDatesArr[2]-1;
            $data['month'] = $birthDatesArr[1]-1;
            $data['year'] = $birthDatesArr[0]-1915;
            $form->picture->setRequired(false);
            $form->email->addValidator(
                'Db_NoRecordExists',
                false,
                array(
                    'table'     => 'user',
                    'field'     => 'email',
                    'exclude'   => array(
                        'field' => 'email',
                        'value' => $data['email']
                    )
                )
            );
            $form->populate($data); 
            if($this->_request->isPost()){
               if($form->isValid($this->_request->getParams())){
                    //
                    //var_dump($_FILES);
                    if($_FILES['picture']['size']== NULL) {
                        //echo 'No upload';
                        $user_info = $form->getValues();
                        $user_info['profileimage'] = $data['profileimage'];
                        echo "there is no new pic";
                    }
                    else{
                        //echo "haaaaaaaaaa new pic";
                        $upload = new Zend_File_Transfer_Adapter_Http();
                
                        //var_dump($upload);
                        $upload->setDestination(APPLICATION_PATH . '/images');

                        try {
                        // upload received file(s)
                            $upload->receive();
                        } catch (Zend_File_Transfer_Exception $e) {
                            $e->getMessage();
                        }
                        
                        $name = $upload->getFileName('picture');
                        
                        $renameFile = 'newName.jpg';

                        $fullFilePath = '../images/'.$renameFile;

                        // Rename uploaded file using Zend Framework
                        $filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));

                        $ret = $filterFileRename -> filter(APPLICATION_PATH . '/images/'.'asd.jpg');

                        $user_info = $form->getValues();
                        $imageName = $user_info['username'].uniqid();
                        //exit;
                        rename($name, APPLICATION_PATH . '/../public/images/users/'.$imageName); 
                        $user_info['profileimage'] = 'images/users/'.$imageName;
                    }
                    
                    $user_model = new Application_Model_User();
                    $user_model->updateUserByID($userid,$user_info);
                    $this->redirect("user/message/msg/useredited");
               }
               else{
                   echo "Your Information is not correct";
                   //var_dump($this->_request->getParams());
               }
               
               }
            $this->view->form = $form;
            
        }
        else{
            $this->_helper->viewRenderer('Error/unauthoriezed', null, true);
        }

        
    }

    public function confirmAction()
    {
        $username = $this->_request->getParam("username");
        $confirmMsg = $this->_request->getParam("cid");
         
        $regiMessage_model = new Application_Model_RegistrationMessage();
        $data = $regiMessage_model->getMessageByUsername($username); 
        if ($data['confirmationNumber'] == $confirmMsg){
             
            $data2['confirmed'] = "1";
            $user_model = new Application_Model_User();
            // $user_model->updateUserByUsername($username, $user_info);
            $user_model->updateUserByUsername($username, $data2);
            $this->redirect("user/message/msg/confsucc");
        }
        else{
            $this->redirect("user/message/msg/confail");
        }
         
    }

    public function logoutAction()
    {
        $authorization = Zend_Auth::getInstance();
        $authorization->clearIdentity();
        $this->redirect("Authentication"); 
    }

    public function profilepageAction()
    {
        $authorization = Zend_Auth::getInstance();
        $userid = $authorization->getIdentity()->id;
        $thisUser = new Application_Model_User();
        $data = $thisUser->getUserByID($userid);
        $thisUserMessage = new Application_Model_PrivateMessage();
        $data2 = $thisUserMessage->getPrivateMessageByRecieverID($userid);
        //var_dump($data);
        $this->view->userData = $data;
        $this->view->userPrivateMsg = $data2;
        //$this->view->userData = $data;
        
    }
    public function messageAction()
    {
        $msgType = $this->_getParam("msg");
        //echo $msgType;
        if($msgType == 'useredited'){
            $this->view->message = "The information has been edited successfuly";
        }
        else if($msgType == 'confsucc'){
            $this->view->message = "Confirmation Successful";
        }
        else if($msgType == 'confail'){
            $this->view->message = "Confirmation Failed";
        }
        else if($msgType == 'regisucc'){
            $this->view->message = "Registration has been successful, Please check your email for the confirmation message";
        }
        else{
            $this->view->message = "Unknown message";
        }
        // action body
    }
    public function friendprofileAction(){
        $friendid = $this->_request->getParam("friendid");
        $thisUser = new Application_Model_User();
        
        $data = $thisUser->getUserByID($friendid);
        //$data = $thisUser->getUserByID(86);
        $this->view->userData = $data;
        
        
        
    }
    
    public function sendmsgAction(){
        //echo $this->_request->getParam("rec_id");
        //echo $this->_request->getParam("message");
        //echo $this->_request->getParam("msgtitle");
        //exit;
        $authorization = Zend_Auth::getInstance();
        if(isset($authorization->getIdentity()->id)){
        $myUserid = $authorization->getIdentity()->id;
        
        $rec_id = $this->_request->getParam("rec_id");
        $msg = $this->_request->getParam("message");
        $title = $this->_request->getParam("msgtitle");
        $thisUserMessage = new Application_Model_PrivateMessage();
        $data['msg_title'] =  $title;
        $data['sendUserID'] =  $myUserid;
        $data['receiveUserID'] =  $rec_id;
        $data['msg_body'] =  $msg;
        //print_r($data);
        $result = $thisUserMessage->addPrivateMessage($data);
            echo '###message sent###';
        }
        else{
            echo '###error###';
        }
    }
    


}









