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

    $target = (string)filter_input(INPUT_GET, 'target');
    $order = (string)filter_input(INPUT_GET, 'order');
    $todo_array = $TODO->read();
    if($target){
        $target_array = [];
        foreach ($todo_array as $key => $value) {
            $target_array[$key] = $value[$target];
        }
        if($order === "asc"){
            array_multisort($target_array, SORT_ASC, $todo_array);
        }elseif($order === "desc"){
            array_multisort($target_array, SORT_DESC, $todo_array);
        }
    }
    $currentDateTime = new DateTimeImmutable();
    $todoList = [];

    foreach ($todo_array as $todo) {
        $finishedDateTime = new DateTimeImmutable($todo['finished_at']);
        $todo['finished'] = $currentDateTime >= $finishedDateTime;
        $todoList[] = $todo;
    }

    $view->assignTodoListToBeDisplayed($todoList);