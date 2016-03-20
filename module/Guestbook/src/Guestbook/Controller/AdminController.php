<?php

namespace Guestbook\Controller;

use Guestbook\Controller\IndexController;
use Zend\Session\Container;

class AdminController extends IndexController
{

    public function showAction() {
        
        $id = $this->params()->fromRoute('id');
        $msg = $this->getMsgTable()->getMessage($id);
        if($msg){
            $this->answer['ok'] = 1;
            $this->answer['data'] = array(
                'user_name' => $msg->user_name,
                'email' => $msg->email,
                'homepage' => $msg->homepage,
                'text' => $msg->text,
                'user_ip' => $msg->user_ip,
                'user_agent' => $msg->user_agent,
            );
        }
        
        echo json_encode($this->answer);
        die();
    }
    
    public function editAction() {
        
        if($this->request->isPost()){
            $id = $this->params()->fromRoute('id');
            $msg = $this->getMsgTable()->getMessage($id);
            $post = $this->request->getPost();
            if(!isset($post->id) && $msg){
                $this->answer['ok'] = 1;
                $this->answer['data'] = array(
                    'id' => $msg->id,
                    'user_name' => $msg->user_name,
                    'email' => $msg->email,
                    'homepage' => $msg->homepage,
                    'text' => $msg->text,
                );
            }else if($msg){
                $session = new Container('guestbook');
                $form = $this->getServiceLocator()->get('EditMessageForm');
                $form->bind($msg);
                $form->setData($post);
                if(!$form->isValid()){
                    $filter = $this->getServiceLocator()->get('EditMessageFilter');
                    $this->answer['ok'] = 0;
                    $this->answer['msg'] = $filter->getInvalidInput();
                }else if($post->captcha !== $session->captcha){
                    $this->answer['ok'] = 0;
                    $this->answer['msg']['captcha'] = 'wrong captcha';
                }else{
                    $msg->short_text = (strlen($msg->text)<100)? 
                            $msg->text: mb_substr($msg->text, 0, 97, 'UTF-8').'...';

                    if($this->getMsgTable()->saveMessage($msg)){
                        $this->answer['ok'] = 1;
                        $this->answer['msg'][] = 'Message was changed';
                    }  else {
                        $this->answer['msg'][] = 'Message was not changed';
                    }
                }
            }
        }
        echo json_encode($this->answer);
        die();
    }
    
    public function deleteAction() {
        $id = $this->params()->fromRoute('id');
        if($this->getMsgTable()->deleteMessage($id)){
            $this->answer['ok'] = 1;
            $this->answer['msg'][] = 'Message was deleted';
        }  else {
            $this->answer['msg'][] = 'Message was not deleted';
        }
        
        echo json_encode($this->answer);
        die();
    }
    
}