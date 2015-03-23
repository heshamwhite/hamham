<?php

class Application_Form_AddPost extends Zend_Form
{

    public function init()
    {
        $this->setMethod("post");
        $forumID = new Zend_Form_Element_Hidden("forumID");
        $user_id = new Zend_Form_Element_Hidden("user_id");
        $postID = new Zend_Form_Element_Hidden("postID");
        $title = new Zend_Form_Element_Text("title");
        //$title->setAttrib("class", "");
        $title->setLabel("title: ");
        $title->setRequired();
        $title->addFilter(new Zend_Filter_StripTags);
        $title->addValidator(new Zend_Validate_Db_NoRecordExists(array(
            'table' => 'post',
            'field' => 'title'
            )));
        
        
        $body = new Zend_Form_Element_Textarea("body");
        $body->setLabel("body: ");
        $body->setRequired();
        $body->addFilter(new Zend_Filter_StripTags);
        $body->setAttrib("id", "noise");
        //$body->setAttrib("name", "noise");
        $body->setAttrib("class", "widgEditor nothing");
        
        $submit = new Zend_Form_Element_Submit("submit");
        $reset = new Zend_Form_Element_Reset("reset");
        
        $this->addElements(array($forumID,$user_id,$postID,$title,$body,$submit,$reset));

    }


}

