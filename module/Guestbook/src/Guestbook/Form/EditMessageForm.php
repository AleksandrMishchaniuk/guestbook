<?php

namespace Guestbook\Form;
use Zend\Form\Form;

class EditMessageForm extends Form {
    
    public function __construct($name = null) {
        parent::__construct('EditMessage');
        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'edit_message_form');
        $this->setAttribute('role', 'form');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
                'value' => 0,
            ),
        ));
        
        $this->add(array(
            'name' => 'user_name',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'User Name',
                'min' => 2,
                'max' => 50,
            ),
        ));
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'E-mail',
                'min' => 5,
                'max' => 100,
            ),
        ));
        
        $this->add(array(
            'name' => 'homepage',
            'attributes' => array(
                'type' => 'url',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Homepage (URL)',
                'max' => 100,
            ),
        ));
        
        $this->add(array(
            'name' => 'text',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control',
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Message',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Create',
                'class' => 'btn btn-primary',
                'form' => 'edit_message_form',
            ),
        ));
    }
    
}
