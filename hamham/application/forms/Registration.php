<?php

class Application_Form_Registration extends Zend_Form
{
    
    const ELEMENT_DAY = 'day';
    const ELEMENT_MONTH = 'month';
    const ELEMENT_YEAR = 'year';
    
    public function getDay() { return $this->getValue(self::ELEMENT_DAY); }
    public function getMonth() { return $this->getValue(self::ELEMENT_MONTH); }
    public function getYear() { return $this->getValue(self::ELEMENT_YEAR); }
    
    public function init()
    {        
        $this->setMethod("post");
        
        $username = new Zend_Form_Element_Text("username");
        $username->setAttrib("class", "form-control");
        $username->setLabel("Username: ");
        $username->setRequired();
        $username->addFilter(new Zend_Filter_StripTags);
        $username->addValidator(new Zend_Validate_Db_NoRecordExists(array(
            'table' => 'user',
            'field' => 'username'
            )));
        
        $firstname = new Zend_Form_Element_Text("firstname");
        $firstname->setAttrib("class", "form-control");
        $firstname->setLabel("First Name: ");
        $firstname->setRequired();
        $firstname->addFilter(new Zend_Filter_StripTags);
        
        $lastname = new Zend_Form_Element_Text("lastname");
        $lastname->setAttrib("class", "form-control");
        $lastname->setLabel("Last Name: ");
        $lastname->setRequired();
        $lastname->addFilter(new Zend_Filter_StripTags);
        
        $email = new Zend_Form_Element_Text("email");
        $email->setAttrib("class", "form-control");
        $email->setLabel("Email: ");
        $email->setRequired();
        $email->setLabel("Email:");
        $email->addValidator(new Zend_Validate_EmailAddress());
        $email->addValidator(new Zend_Validate_Db_NoRecordExists(array(
            'table' => 'user',
            'field' => 'email'
            )));

        $password = new Zend_Form_Element_Password("password");
        $password->setRequired();
        $password->setLabel("Password");
        
        
        $userTypes = new Application_Model_UserType();
        $userType_list = $userTypes->getUserTypeList();
        
        $usertype = new Zend_Form_Element_Select("usertype");
        $usertype->setLabel("Experience Level");
        
        $userTypeOptions = array();

        foreach ($userType_list as $value) {
            $userTypeOptions[$value['id']] = $value['type'];
        }
        $usertype->setMultiOptions($userTypeOptions);
        $usertype->addValidator(new Zend_Validate_Int(), false);
        $usertype->addValidator(new Zend_Validate_GreaterThan(-1), false);
               
        $gender = new Zend_Form_Element_Select("gender");
        $gender->setLabel("Gender");
        $gender->setMultiOptions(array(
            -1 => 'Gender',
            0 => 'Male',
            1 => 'Female'
        ));
        $gender->addValidator(new Zend_Validate_Int(), false);
        $gender->addValidator(new Zend_Validate_GreaterThan(-1), false);
        
        
        $countries = new Application_Model_Country();
        $countries_list = $countries->getCountriesList();
        $country = new Zend_Form_Element_Select('country');
        $country->setLabel('Country:');
        $options = array();

        foreach ($countries_list as $value) {
            $options[$value['id']] = $value['name'];
        }
        $country->setMultiOptions($options);
        
        $dob_days = $this->createElement('Select', self::ELEMENT_DAY);
        $dob_days->setMultiOptions(range(1, 31));
        
        
        $dob_months = $this->createElement('Select', self::ELEMENT_MONTH);
        
        $monthOptions = array();
        $months = array("January","February","March","April","May","June","July","August","September","October","November","December");
        for($i=0;$i<12;$i++){   
            $monthOptions[$i]= $months[$i];
        }
        
        $dob_months->setMultiOptions($monthOptions);
   
        $dob_years = $this->createElement('Select', self::ELEMENT_YEAR);
        $dob_years->setMultiOptions(range(idate('Y') - 100, idate('Y')));

        $picture = new Zend_Form_Element_File('picture', array(
            'label' => 'Picture',
            'required' => true,
            'MaxFileSize' => 2097152, // 2097152 BYTES = 2 MEGABYTES
            'validators' => array(
                array('Count', false, 1),
                array('Size', false, 2097152),
                array('Extension', false, 'gif,jpg,png'),
                array('ImageSize', false, array('minwidth' => 100,
                                                'minheight' => 100,
                                                'maxwidth' => 1000,
                                                'maxheight' => 1000))
            )
        ));
        //$picture->setDestination(APPLICATION_PATH . '/images');
        //$picture->setValueDisabled(true);
        
        
        $captcha = new Zend_Form_Element_Captcha('foo', array(
            'label' => "Please verify you're a human",
            'captcha' => array(
                'captcha' => 'Figlet',
                'wordLen' => 6,
                'timeout' => 300,
            ),
        ));
        
        
        
        //$id = new Zend_Form_Element_Hidden("id");
        
        $submit = new Zend_Form_Element_Submit("submit");
        
        $reset = new Zend_Form_Element_Reset("reset");
        
        $this->addElements(array($username,$firstname,$lastname,$email,$password,$usertype,$gender,$country,$captcha,$dob_days,$dob_months,$dob_years,$picture,$submit,$reset));

        
    }


}

