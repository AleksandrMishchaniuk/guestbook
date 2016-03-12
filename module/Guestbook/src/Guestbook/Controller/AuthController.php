<?php

namespace Guestbook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;

class AuthController extends AbstractActionController
{
    
    public function loginAction() {
        if($this->request->isPost()){
            $data = $this->request->getPost();
            
            $config = $this->getServiceLocator()->get('Config');
            if(isset($data['adm_pwd']) && isset($config['guestbook_guard']['admin_password_hash'])){
                $hash = $config['guestbook_guard']['admin_password_hash'];
                if($hash === md5($data['adm_pwd'])){
                    $session = new Container('guestbook');
                    $session->user = 'admin';
                    $this->toAdminAction();
                }else{
                    $this->flashMessenger()
                            ->addErrorMessage('Entered wrong password');
                    $this->toIndexAction();
                }
            }
        }
    }
    
    public function logoutAction() {
        $session = new Container('guestbook');
        if(isset($session->user)){
            unset($session->user);
        }
        $this->toIndexAction();
    }
    
    public function toIndexAction() {
        $this->redirect()->toRoute('guestbook', array('controller'=>'index'));
    }
    
    public function toAdminAction() {
        $this->redirect()->toRoute('guestbook', array('controller'=>'admin'));
    }
    
}