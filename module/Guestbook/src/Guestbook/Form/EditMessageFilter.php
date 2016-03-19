<?php

namespace Guestbook\Form;

use Zend\InputFilter\InputFilter;

class EditMessageFilter extends InputFilter {
    
    public function __construct() {
        
        $this->add(array(
            'name' => 'id',
            'validators' => array(
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/^[0-9]+$/',
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'user_name',
            'required' => TRUE,
            'filters' => array(
                array(
                    'name' => 'StringTrim'
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/^[ 0-9A-Za-z]{2,50}$/',
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'email',
            'required' => TRUE,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 5,
                        'max' => 100,
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'homepage',
            'required' => FALSE,
            'validators' => array(
                array(
                    'name' => 'Uri',
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'max' => 100,
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'text',
            'required' => TRUE,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
                array(
                    'name' => 'HtmlEntities',
                    'options' => array(
                        'quotestyle' => ENT_QUOTES,
                    ),
                ),
            ),
        ));
        
    }
    
}
