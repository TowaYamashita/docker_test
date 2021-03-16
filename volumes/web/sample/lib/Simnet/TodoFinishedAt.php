<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class TodoFinishedAt
{
    private static $DEFAULT_FINISHED_AT = "";
    private $finished_at;

    public function __construct(?string $finished_at){
        $this->finished_at = $this->validate($finished_at);
    }

    /**
     * get title
     *
     * @return string
     */
    public function get():string{
        return $this->finished_at;
    }

    private function validate(?string $finished_at):string{
        // e.g. 2021-04-01T12:00
        $pattern = "/\A[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])T([01][0-9]|2[0-3]):[0-5][0-9]\z/";

        if(is_null($finished_at) || !preg_match($pattern, $finished_at)){
            return self::$DEFAULT_FINISHED_AT;
        }

        return $finished_at;
    }
}