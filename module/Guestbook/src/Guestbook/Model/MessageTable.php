<?php
namespace Guestbook\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Guestbook\Model\Message as Message;
use Exception;
use Zend\Db\Sql\Select;

class MessageTable {
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveMessage(Message $message) {
        $data = array();
        foreach ($message as $key => $val){
            if($key !== 'id'){
                $data[$key] = $val;
            }
        }
        $id = (int)$message->id;
        if(!$id){
            $this->tableGateway->insert($data);
            return TRUE;
        }else{
            if($this->getMessage($id)){
                $this->tableGateway->update($data, array('id' => $id));
                return TRUE;
            }else{
                throw new Exception('User ID does not exist');
                return FALSE;
            }
        }
    }
    
    public function getMessage($id) {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if(!$row){
            throw new Exception("Could not find row $id");
            return FALSE;
        }
        return $row;
    }
    
    public function fetchAll() {
        $select = new Select;
        $select->from($this->tableGateway->getTable())->order('id DESC');
        return $this->tableGateway->selectWith($select);
    }
    
    public function deleteMessage($id) {
        $id = (int)$id;
        if(!$this->tableGateway->delete(array('id'=>$id))){
            throw new Exception("Could not find row $id");
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    public function fetchSorted(array $data) {
        $order = array();
        $where = array();
        if(isset($data['sort'])){
            foreach ($data['sort'] as $field => $value) {
                switch ($value){
                    case '_none_':
                        break;
                    case '_ASC_':
                        $order[] = $field.' ASC';
                        break;
                    case '_DESC_':
                        $order[] = $field.' DESC';
                        break;
                    default:
                        $where[$field] = $value;
                        break;
                }
            }
        }
        $order[] = 'id DESC';
        
        $select = new Select;
        $select->from($this->tableGateway->getTable())
                ->order($order)
                ->where($where);
        
        if(isset($data['paginator'])){
            $select->limit((int) $data['paginator']['limit'])
                    ->offset((int) $data['paginator']['offset']);
        }
        
        return $this->tableGateway->selectWith($select);
    }
    
    public function fetchUsers() {
        $adapter = $this->tableGateway->getAdapter();
        $table = $this->tableGateway->getTable();
        $sql = "SELECT user_name FROM $table"
                . " GROUP BY user_name"
                . " ORDER BY user_name ASC";
        $stm = $adapter->query($sql);
        $result = $stm->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $items = array();
        foreach ($resultSet as $row){
            $items[] = $row->user_name;
        }
        return $items;
    }
    
    public function fetchEmails() {
        $adapter = $this->tableGateway->getAdapter();
        $table = $this->tableGateway->getTable();
        $sql = "SELECT email FROM $table"
                . " GROUP BY email"
                . " ORDER BY email ASC";
        $stm = $adapter->query($sql);
        $result = $stm->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $items = array();
        foreach ($resultSet as $row){
            $items[] = $row->email;
        }
        return $items;
    }
    
    public function getCount($data) {
        $where = array();
        if(isset($data['sort'])){
            foreach ($data['sort'] as $field => $value) {
                switch ($value){
                    case '_none_':
                    case '_ASC_':
                    case '_DESC_':
                        break;
                    default:
                        $where[] = "$field = '$value'";
                        break;
                }
            }
        }
        if(!empty($where)){
            $where = 'WHERE '.implode(' AND ', $where);
        }  else {
            $where = '';
        }
        
        $adapter = $this->tableGateway->getAdapter();
        $table = $this->tableGateway->getTable();
        $sql = "SELECT COUNT(*) AS cnt FROM $table $where";
        $stm = $adapter->query($sql);
        $result = $stm->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $item = NULL;
        foreach ($resultSet as $row){
            $item = $row->cnt;
        }
        return $item;
    }
    
}
