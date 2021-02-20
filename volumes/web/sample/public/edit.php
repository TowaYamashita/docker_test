<?php
    require_once __DIR__ . '/../lib/bootstrap.php';

    $mode = (string)filter_input(INPUT_GET, 'mode');

    if($mode === "edit"){
        $id = (int)filter_input(INPUT_GET, 'id');
        $TODO = new Simnet\TodoStore();
        $todoList = $TODO->findById($id);

        $view = new Simnet\ViewControler(basename($_SERVER['SCRIPT_NAME']), null);
        $view->assignTodoListToBeDisplayed($todoList);
    }
