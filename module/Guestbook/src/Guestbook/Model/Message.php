<?php

namespace Guestbook\Model;

class Message {
    public $id;
    public $user_name;
    public $email;
    public $homepage;
    public $text;
    public $short_text;
    public $user_ip;
    public $user_agent;
    
    public function exchangeArray($data){
        foreach ($data as $key => $val){
            if(property_exists($this, $key)){
                $this->$key = $val;
            }
        }
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}
