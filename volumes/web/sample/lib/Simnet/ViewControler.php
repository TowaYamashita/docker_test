<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class ViewControler
{
    private const TEMPLATE_DIR = __DIR__ . '/../../template/';

    private $filename;
    private $title;
    private $todoList = [];
    private $alert_message = [
        "type" => "",
        "body" => ""
    ];

    /**
     * initialize
     *
     * @param string $filename optional defaults index.php
     * @param string $title optional  defaults TODO Application
     */
    public function __construct(?string $filename, ?string $title){
        $this->filename = $filename ?? 'index.php';
        $this->title    = $title ?? 'TODO Application';
    }

    /**
     * set TodoList to be displayed
     *
     * @param array $todoList
     */
    public function assignTodoListToBeDisplayed(array $todoList){
        $this->todoList = $todoList;
    }

    /**
     * set AlertMessage to be displayed
     *
     * @param array $alert_message
     */
    public function assignAlertMessage(array $alert_message){
        $this->alert_message = $alert_message;
    }

    public function __destruct(){
        $title    = $this->title;
        $header   = $this->html_header();
        $script   = $this->html_script();
        $footer   = $this->html_footer();

        $alert_message = $this->alert_message;
        $todoList      = $this->todoList;

        require_once self::TEMPLATE_DIR . $this->filename;
    }

    /**
     * return html header
     *
     * @return string
     */
    private function html_header(){
        return <<<HTML
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$this->title}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
HTML;
    }

    /**
     * return html footer
     *
     * @return string
     */
    private function html_footer(){
        return <<<HTML
</body>
</html>
HTML;
    }

    private function html_script(){
        return <<<HTML
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script defer src="https://use.fontawesome.com/releases/v5.6.3/js/all.js"></script>
<script src="js/finishButton.js"></script>
<script src="js/deleteButton.js"></script>
<script src="js/editButton.js"></script>
HTML;
    }

}