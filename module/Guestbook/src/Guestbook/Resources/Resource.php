<?php

namespace Guestbook\Resources;

use Zend\Permissions\Acl\Resource\ResourceInterface;

class Resource implements ResourceInterface{
    protected $resource_id;
    public function __construct($resource) {
        $this->resource_id = $resource;
    }
    public function getResourceId() {
        return $this->resource_id;
    }
}