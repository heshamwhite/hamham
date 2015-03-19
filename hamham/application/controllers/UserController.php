<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
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
               $cid = "eeee";
               $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
                shuffle($seed); // probably optional since array_is randomized; this may be redundant
                $cid= substr(implode('', $seed), 1, 51);
               $user_info = $form->getValues();
                 
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
           }
           else{
               echo "bad";
               //var_dump($this->_request->getParams());
           }
        }
       
        $this->view->form = $form;
        
    }

    public function editprofileAction()
    {
        // action body
        
        $userid = $this->_request->getParam("userid");
        $form  = new Application_Form_Edit();
        $thisUser = new Application_Model_User();
        $data = $thisUser->getUserByID($userid);
        $birthDatesArr = explode('-', $data['birth_date']);
        $data['day'] = $birthDatesArr[2]-1;
        $data['month'] = $birthDatesArr[1]-1;
        $data['year'] = $birthDatesArr[0]-1915;
        $form->populate($data);
        if($this->_request->isPost()){
           if($form->isValid($this->_request->getParams())){
                $user_info = $form->getValues();
                $user_model = new Application_Model_User();
                $user_model->updateUserByID(35,$user_info);
           }
           else{
               echo "bad";
               //var_dump($this->_request->getParams());
           }
        }
       
        $this->view->form = $form;
        
        
        
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
             echo 'Confirmation Successful';
         }
         else{
             echo 'Confirmation Failed';
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
        $thisUserNessage = new Application_Model_PrivateMessage();
        $data2 = $thisUserNessage->getPrivateMessageByRecieverID($userid);
        //var_dump($data);
        $this->view->userData = $data;
        $this->view->userPrivateMsg = $data2;
        //$this->view->userData = $data;
        
    }


}









