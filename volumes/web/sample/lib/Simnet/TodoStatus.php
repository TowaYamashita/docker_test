<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class TodoStatus
{
    private static $DB;
    private static $DEFAULT_STATUS = "todo";
    private static $STATUS_LEVEL;
    private $status;

    public function __construct(?string $status){
        self::$DB           = \Simnet\Database::getPDO();
        self::$STATUS_LEVEL = self::fetchStatusFromDB();
        $this->status = $this->validate($status);
    }

    /**
     * get current_level status
     *
     * @return string
     */
    public function get():string{
        return $this->status;
    }

    public function advance(){
        switch($this->status){
            case "todo":
                $this->status = "doing";
                break;
            case "doing":
                $this->status = "done";
                break;
            case "done":
                $this->status === "done";
                break;
        }
    }
    /**
     * get next_level status
     *
     * @return string
     */
    public function getNextStatus():string{
        switch($this->status){
            case "todo":
                return "doing";
                break;
            case "doing":
                return "done";
                break;
            case "done":
                return "done";
                break;
        }
    }

    private function validate(?string $status):string{
        if(is_null($status)){
            return self::$DEFAULT_STATUS;
        }
        return in_array($status, self::$STATUS_LEVEL, true) ? $status : self::$DEFAULT_STATUS;
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
