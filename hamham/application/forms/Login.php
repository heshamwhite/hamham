<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
       $this->setMethod("post");
         
        $email = new Zend_Form_Element_Text("username");
        $email->setRequired()
                ->setLabel("Username:");
            
         
        $password = new Zend_Form_Element_Password("password");
        $password->setRequired()
                 ->setLabel("Password");
         
        $submit = new Zend_Form_Element_Submit("login");
        $this->addElements(array($email,$password,$submit));
    }


}

