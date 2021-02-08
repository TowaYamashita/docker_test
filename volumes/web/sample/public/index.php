<?php
    require_once __DIR__ . '/../lib/bootstrap.php';

    $mode = (string)filter_input(INPUT_POST, 'mode');
    $TODO = new Simnet\TodoListManager();

    if ($mode === 'create') {
        $todo = trim((string)filter_input(INPUT_POST, 'todo'));
        $result = $TODO->create($todo);
        echo $result ? 'finished' : 'error' ;
    }

    $todoList = [];
    $currentDateTime = new DateTimeImmutable();

    foreach ($TODO->read() as $todo) {
        $finishedDateTime = new DateTimeImmutable($todo['finished_at']);
        $todo['finished'] = $currentDateTime >= $finishedDateTime;
        $todoList[] = $todo;
    }

    $view = new Simnet\ViewControler(basename($_SERVER['SCRIPT_NAME']), null);
    $view->assignTodoListToBeDisplayed($todoList);