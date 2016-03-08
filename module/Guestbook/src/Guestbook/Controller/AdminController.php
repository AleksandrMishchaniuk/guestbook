<?php

namespace Guestbook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Guestbook\Controller\IndexController;

class AdminController extends IndexController
{
    public function indexAction()
    {
        return array();
    }
    
    public function loginAction() {
        if($this->request->isPost()){
            $data = $this->request->getPost();
            
            $config = $this->getServiceLocator()->get('Config');
            if(isset($data['adm_pwd']) && isset($config['guestbook_guard']['admin_password_hash'])){
                $hash = $config['guestbook_guard']['admin_password_hash'];
                if($hash === md5($data['adm_pwd'])){
                    $session = new Container('guestbook');
                    $session->user = 'admin';
                }else{
                    $this->flashMessenger()
                            ->addErrorMessage('Entered wrong password');
                }
            }
        }
        $this->redirect()->toRoute('guestbook');
    }
    
    public function logoutAction() {
        $session = new Container('guestbook');
        if(isset($session->user)){
            unset($session->user);
        }
        $this->redirect()->toRoute('guestbook');
    }
    
    public function authFalseAction() {
        $this->redirect()->toRoute('guestbook');
    }
    
    public function toAdminAction() {
        $this->redirect()->toRoute('guestbook', array('controller'=>'admin'));
    }
}