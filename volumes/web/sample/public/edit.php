<?php
    require_once __DIR__ . '/../lib/bootstrap.php';

    $mode = (string)filter_input(INPUT_POST, 'mode');
    $TODO = new Simnet\TodoListManager();

    if($mode === "edit"){
        $id = (int)filter_input(INPUT_POST, 'id');
        $todoList = [];
        $todoList = $TODO->findById($id);
        $view = new Simnet\ViewControler(basename($_SERVER['SCRIPT_NAME']), null);
        $view->assignTodoListToBeDisplayed($todoList);
    }
