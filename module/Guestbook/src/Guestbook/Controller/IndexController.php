<?php

namespace Guestbook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Guestbook\Form\LoginForm;
use Guestbook\Model\Message;
use Gregwar\Captcha\CaptchaBuilder;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    protected $answer;
    protected $msg_table;

    public function __construct() {
        $this->answer = array(
            'ok' => 0,
            'msg' => array(),
            'data' => array(),
        );
    }
    
    protected function getMsgTable(){
        if(!$this->msg_table){
            $this->msg_table = $this->getServiceLocator()->get('MessageTable');
        }
        return $this->msg_table;
    }

    public function indexAction()
    {   
        $login_form = new LoginForm();
        
        return array(
            'login_form' => $login_form,
        );
    }
    
    /**
     * 
     */
    public function getListAction() {
        $msgs = array();
        $count = NULL;
        $offset = NULL;
        if($this->request->isPost()){
            $post = $this->request->getPost();
            if(!empty($post)){
                $data = array();
                if(isset($post['user_name']) && isset($post['email'])){
                    $data['sort'] = array(
                        'user_name' => $post['user_name'],
                        'email' => $post['email'],
                    );
                }
                if(isset($post['limit']) && isset($post['offset'])){
                    $data['paginator'] = array(
                        'limit' => $post['limit'],
                        'offset' => $post['offset'],
                    );
                    $offset = $post['offset'];
                }
                $msgs = $this->getMsgTable()->fetchSorted($data);
                $count = $this->getMsgTable()->getCount($data);
            }else{
                $msgs = $this->getMsgTable()->fetchAll();
            }
        }
        $this->answer['ok'] = 1;
        foreach ($msgs as $msg) {
            $this->answer['data']['msgs'][] = array(
                'id' => $msg->id,
                'user_name' => $msg->user_name,
                'email' => $msg->email,
                'short_text' => $msg->short_text,
            );
        }
        $this->answer['data']['paginator']['count'] = $count;
        $this->answer['data']['paginator']['offset'] = $offset;
        echo json_encode($this->answer);
        die();
    }
    
    /**
     * 
     */
    public function getSortAction() {
        $this->answer['data']['user_names'] = $this->getMsgTable()->fetchUsers();
        $this->answer['data']['emails'] = $this->getMsgTable()->fetchEmails();
        $this->answer['ok'] = 1;
        echo json_encode($this->answer);
        die();
    }
    
    
    public function createAction() {
        
        if($this->request->isPost()){
            $session = new Container('guestbook');
            $post = $this->request->getPost();
            $form = $this->getServiceLocator()->get('EditMessageForm');
            $form->setData($post);
            if(!$form->isValid()){
                $filter = $this->getServiceLocator()->get('EditMessageFilter');
                $this->answer['ok'] = 0;
                $this->answer['msg'] = $filter->getInvalidInput();
            }else if($post->captcha !== $session->captcha){
                $this->answer['ok'] = 0;
                $this->answer['msg']['captcha'] = 'wrong captcha';
            }else{
                $data = $form->getData();
                $data['short_text'] = (strlen($data['text'])<100)? 
                        $data['text']: mb_substr($data['text'], 0, 97, 'UTF-8').'...';
                $data['user_ip'] = $_SERVER['REMOTE_ADDR'];
                $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

                $msg = new Message();
                $msg->exchangeArray($data);
                if($this->getMsgTable()->saveMessage($msg)){
                    $this->answer['ok'] = 1;
                    $this->answer['msg'][] = 'New Message was created';
                }  else {
                    $this->answer['msg'][] = 'Message was not created';
                }
            }
        }
        echo json_encode($this->answer);
        die();
        
    }
    
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
            );
        }
        
        echo json_encode($this->answer);
        die();
    }
    
    
    public function captchaAction() {
        $session = new Container('guestbook');
        
        $builder = new CaptchaBuilder;
        $builder->build();
        
        $session->captcha = $builder->getPhrase();
        
        echo $builder->inline();
        die();
    }
    
}