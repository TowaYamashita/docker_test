<?php
    require_once __DIR__ . '/../lib/bootstrap.php';

    $mode = (string)filter_input(INPUT_POST, 'mode');
    $TODO = new Simnet\TodoListManager();
    $view = new Simnet\ViewControler(basename($_SERVER['SCRIPT_NAME']), null);

    switch($mode){
        case "create":
            $title    = trim((string)filter_input(INPUT_POST, 'title'));
            $deadline = trim((string)filter_input(INPUT_POST, 'deadline'));
            $result = $TODO->create($title,$deadline);
            $view->assignAlertMessage($mode = "create", $result);
            break;
        case "update":
            $id       = (int)filter_input(INPUT_POST, 'id');
            $title    = trim((string)filter_input(INPUT_POST, 'title'));
            $deadline = trim((string)filter_input(INPUT_POST, 'deadline'));
            $updated_todo = [
                "title"       => $title,
                "finished_at" => $deadline
            ];
            $result = $TODO->update($id, $updated_todo);
            $view->assignAlertMessage($mode = "update", $result);
            break;
        case "delete":
            $id = (int)filter_input(INPUT_POST, 'id');
            $result = $TODO->delete($id);
            $view->assignAlertMessage($mode = "delete", $result);
            break;
        case "finish":
            $id = (int)filter_input(INPUT_POST, 'id');
            $result = $TODO->finish($id);
            $view->assignAlertMessage($mode = "finish", $result);
            break;
    }

    $todoList = [];
    $currentDateTime = new DateTimeImmutable();

    foreach ($TODO->read() as $todo) {
        $finishedDateTime = new DateTimeImmutable($todo['finished_at']);
        $todo['finished'] = $currentDateTime >= $finishedDateTime;
        $todoList[] = $todo;
    }

    $view->assignTodoListToBeDisplayed($todoList);