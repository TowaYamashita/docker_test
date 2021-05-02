<?php

namespace Config\Todo;

require_once __DIR__ . '/../../../vendor/autoload.php';

class Title
{
    private static $DEFAULT_TITLE = "NO TITLE";
    private $title;

    public function __construct(?string $title){
        $this->title = $this->validate($title);
    }

    /**
     * get title
     *
     * @return string
     */
    public function get():string{
        return $this->title;
    }

    private function validate(?string $title):string{
        if(is_null($title)){
            return self::$DEFAULT_TITLE;
        }

        if(mb_strlen($title, "UTF-8") > 140){
            $title = mb_substr($title, 140, 140, "UTF-8");
        }
        return $title;
    }
}
