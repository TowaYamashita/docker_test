<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class TodoStatus
{
    private static $DB;
    private static $DEFAULT_STATUS = "todo";
    private static $STATUS_LEVEL;
    private $status;

    public function __construct(string $status){
        self::$DB           = \Simnet\Database::getPDO();
        self::$STATUS_LEVEL = self::fetchStatusFromDB();
        $this->status = in_array($status, self::$STATUS_LEVEL, true) ? $status : self::$DEFAULT_STATUS;
    }

    /**
     * get current_level status
     *
     * @return string
     */
    public function getCurrentStatus():string{
        return $this->status;
    }

    /**
     * get next_level status
     *
     * @return string
     */
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

    /**
     * pick status by checked status
     *
     * @param array $checked_status_to_be_displayed nullable
     * @param array $todo_list
     * @return array
     */
    public static function pickStatusByChecked(?array $checked_status_to_be_displayed):array{
        if(!isset(self::$STATUS_LEVEL)){
            self::$STATUS_LEVEL = self::fetchStatusFromDB();
        }

        if(is_null($checked_status_to_be_displayed)){
            return self::$STATUS_LEVEL;
        }
        $checked_status_to_be_displayed = array_values(array_intersect($checked_status_to_be_displayed, self::$STATUS_LEVEL));

        return $checked_status_to_be_displayed;
    }

    public static function fetchStatusFromDB(){
        if(!isset(self::$DB)){
            self::$DB = \Simnet\Database::getPDO();
        }

        $sql = <<< SQL
        select status
        from todo_statuses
        SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [] ;
    }
}
