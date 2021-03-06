<?php

namespace Guestbook\Form;
use Zend\Form\Form;

class LoginForm extends Form {
    
    public function __construct($name = null) {
        parent::__construct('Login');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'admin_login_form');
        $this->setAttribute('role', 'form');
        
        $this->add(array(
            'name' => 'adm_pwd',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Administrator Password',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Login',
                'class' => 'btn btn-primary',
                'form' => 'admin_login_form',
            ),
        ));
    }
    
}
