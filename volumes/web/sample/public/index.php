<?php
    require_once __DIR__ . '/../lib/bootstrap.php';

    $mode = (string)filter_input(INPUT_POST, 'mode');
    $TODO = new Simnet\TodoStore();
    $view = new Simnet\ViewControler(basename($_SERVER['SCRIPT_NAME']), null);

    switch($mode){
        case "create":
            $title       = trim((string)filter_input(INPUT_POST, 'title'));
            $finished_at = trim((string)filter_input(INPUT_POST, 'finished_at'));
            $created_todo = [
                "title"       => $title,
                "finished_at" => $finished_at
            ];
            $result = $TODO->create($created_todo);
            $view->assignAlertMessage($mode = "create", $result);
            break;
        case "update":
            $id          = (int)filter_input(INPUT_POST, 'id');
            $title       = trim((string)filter_input(INPUT_POST, 'title'));
            $finished_at = trim((string)filter_input(INPUT_POST, 'finished_at'));
            $updated_todo = [
                "title"       => $title,
                "finished_at" => $finished_at
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