<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class TodoStatus
{
    private $status;

    public function __construct($status){
        $this->status = in_array($status, ["todo", "doing", "done"], true) ? $status : "todo";
    }

    public function getStatus(){
        return $this->status;
    }
}
