<?php

namespace Config;

require_once __DIR__ . '/../../vendor/autoload.php';

class AlertMessageMaker
{
    private $mode = "";
    private $result = false;

    /**
     * initialize
     *
     * @param string $mode
     * @param bool $result
     */
    public function __construct(string $mode, bool $result){
        $this->mode   = in_array($mode, ["create", "update", "delete", "change_status"], true) ? $mode : "";
        $this->result = ($this->mode !== "") ? $result : false;
    }

    /**
     * return AlertMessage
     *
     * @return array
     */
    public function getAlertMessage():array{
        return $this->makeAlertMessage($this->mode, $this->result);
    }

    /**
     * make AlertMessage
     *
     * @param string $mode
     * @param bool   $result
     */
    private function makeAlertMessage(string $mode, bool $result):array{
        $alert_message = [
            "type" => "",
            "body" => ""
        ];

        if($result === false){
            $alert_message = [
                "type" => "error",
                "body" => "エラーが発生しました"
            ];
            return $alert_message;
        }

        $alert_message["type"] = "success";
        switch($mode){
            case "create":
                $alert_message["body"] = "正常にTODOが追加されました";
                break;
            case "update":
                $alert_message["body"] = "正常にTODOが更新されました";
                break;
            case "delete":
                $alert_message["body"] = "正常にTODOが削除されました";
                break;
            case "change_status":
                $alert_message["body"] = "TODOの状態が変更されました";
                break;
        }

        return $alert_message;
    }
}