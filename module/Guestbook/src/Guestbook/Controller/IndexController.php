<?php

namespace Guestbook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Guestbook\Form\LoginForm;
use Guestbook\Form\EditMessageForm;
use Guestbook\Form\EditMessageFilter;
use Zend\Db\ResultSet\ResultSet;
use Guestbook\Model\Message;
use Guestbook\Model\MessageTable;
use Zend\Db\TableGateway\TableGateway;

class IndexController extends AbstractActionController
{
    protected $answer;
    
    public function __construct() {
        $this->answer = array(
            'ok' => 0,
            'msg' => array(),
            'data' => array(),
        );
    }

    public function indexAction()
    {   
        $login_form = new LoginForm();
        return array(
            'login_form' => $login_form,
        );
    }
    
    public function createAction() {
        if(!$this->request->isPost()){
            $this->redirect()->toRoute('guestbook');
        }
        $post = $this->request->getPost();
        $form = new EditMessageForm();
        $filter = new EditMessageFilter();
        $form->setInputFilter($filter);
        $form->setData($post);
        if(!$form->isValid()){
            $this->answer['ok'] = 0;
            $this->answer['msg'] = $filter->getInvalidInput();
        }else{
            $data = $form->getData();
            $data['short_text'] = (strlen($data['text'])<100)? 
                    $data['text']: mb_substr($data['text'], 0, 97, 'UTF-8').'...';
            $data['user_ip'] = $_SERVER['REMOTE_ADDR'];
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $this->createMessage($data);
            
            $this->answer['ok'] = 1;
            $this->answer['msg'][] = 'New Message was created';
            // получение всех записей из базы  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $this->answer['data'] = array();
        }
        
        echo json_encode($this->answer);
        die();
    }
    
    protected function createMessage(array $data) {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $rs = new ResultSet();
        $rs->setArrayObjectPrototype(new Message());
        $tableGateway = new TableGateway('message', $dbAdapter, NULL, $rs);
        $message = new Message();
        $message->exchangeArray($data);
        $messageTable = new MessageTable($tableGateway);
        $messageTable->saveMessage($message);
        return TRUE;
    }
    
}