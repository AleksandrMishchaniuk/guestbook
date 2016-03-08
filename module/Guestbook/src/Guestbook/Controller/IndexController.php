<?php

namespace Guestbook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Guestbook\Form\LoginForm;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {   
        $form = new LoginForm();
        return array('form'=>$form);
    }
    
}