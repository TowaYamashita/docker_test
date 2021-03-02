<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class TodoStatus
{
    private static $DB;
    private static $DEFAULT_STATUS = "todo";
    private static $STATUS_LEVEL = [
        "todo",
        "doing",
        "done"
    ];
    private $status;

    public function __construct($status){
        self::$DB           = \Simnet\Database::getPDO();
        self::$STATUS_LEVEL = $this->fetchStatusFromDB();
        $this->status = in_array($status, self::$STATUS_LEVEL, true) ? $status : self::$DEFAULT_STATUS;
    }

    public function getCurrentStatus():string{
        return $this->status;
    }

    public function getNextStatus():string{
        if($this->status === "done"){
            return "done";
        }
        switch($this->status){
            case "todo":
                return "doing";
                break;
            case "doing":
                return "done";
                break;
        }
    }

    private function fetchStatusFromDB(){
        $sql = <<< SQL
        select status
        from todo_statuses
        SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [] ;
    }
}
