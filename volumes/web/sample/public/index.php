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
            $status      = trim((string)filter_input(INPUT_POST, 'status'));
            $updated_todo = [
                "title"       => $title,
                "finished_at" => $finished_at,
                "status"      => $status
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

    $sort_key                       = (string)filter_input(INPUT_GET, 'sort_key');
    $sort_order                     = (string)filter_input(INPUT_GET, 'sort_order');
    $checked_status_to_be_displayed = filter_input(INPUT_GET, 'checked_status_to_be_displayed', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $todo_list_picked_by_status = $TODO->pickByCheckedStatus($checked_status_to_be_displayed ,$TODO->read());
    $todo_list_sorted           = $TODO->sortByKey($sort_key, $sort_order, $todo_list_picked_by_status);

    $currentDateTime = new DateTimeImmutable();
    $todo_list_to_be_displayed = [];
    foreach ($todo_list_sorted as $todo){
        $finishedDateTime = new DateTimeImmutable($todo['finished_at']);
        $todo['finished'] = $currentDateTime >= $finishedDateTime;
        $todo_list_to_be_displayed[] = $todo;
    }

    $view->assignTodoListToBeDisplayed($todo_list_to_be_displayed);