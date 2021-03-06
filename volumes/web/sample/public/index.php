<?php
    require_once __DIR__ . '/../lib/bootstrap.php';

    $mode = (string)filter_input(INPUT_POST, 'mode');
    $TODO = new Config\TodoStore();
    $view = new Config\ViewControler(basename($_SERVER['SCRIPT_NAME']), null);

    switch($mode){
        case "create":
            $title_raw       = trim((string)filter_input(INPUT_POST, 'title'));
            $finished_at_raw = trim((string)filter_input(INPUT_POST, 'finished_at'));
            $created_todo = [
                "title"       => ((new Config\Todo\Title($title_raw))->get()),
                "finished_at" => ((new Config\Todo\FinishedAt($finished_at_raw))->get())
            ];
            $result = $TODO->create($created_todo);
            $alert_message_maker = new Config\AlertMessageMaker("create", $result);
            $view->assignAlertMessage($alert_message_maker->getAlertMessage());
            break;
        case "update":
            $id              = (int)filter_input(INPUT_POST, 'id');
            $title_raw       = trim((string)filter_input(INPUT_POST, 'title'));
            $finished_at_raw = trim((string)filter_input(INPUT_POST, 'finished_at'));
            $status_raw      = trim((string)filter_input(INPUT_POST, 'status'));
            $updated_todo    = [
                "title"       => ((new Config\Todo\Title($title_raw))->get()),
                "finished_at" => ((new Config\Todo\FinishedAt($finished_at_raw))->get()),
                "status"      => ((new Config\Todo\Status($status_raw))->get())
            ];
            $result = $TODO->update($id, $updated_todo);
            $alert_message_maker = new Config\AlertMessageMaker("update", $result);
            $view->assignAlertMessage($alert_message_maker->getAlertMessage());
            break;
        case "delete":
            $id = (int)filter_input(INPUT_POST, 'id');
            $result = $TODO->delete($id);
            $alert_message_maker = new Config\AlertMessageMaker("delete", $result);
            $view->assignAlertMessage($alert_message_maker->getAlertMessage());
            break;
        case "change_status":
            $id = (int)filter_input(INPUT_POST, 'id');
            $result = $TODO->changeTodoStatus($id);
            $alert_message_maker = new Config\AlertMessageMaker("change_status", $result);
            $view->assignAlertMessage($alert_message_maker->getAlertMessage());
            break;
    }

    $sort_key                       = (string)filter_input(INPUT_GET, 'sort_key');
    $sort_order                     = (string)filter_input(INPUT_GET, 'sort_order');
    $checked_status_to_be_displayed = filter_input(INPUT_GET, 'checked_status_to_be_displayed', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $todo_list_picked_by_status = $TODO->pickTodoByCheckedStatus(Config\Todo\Status::pickStatusByChecked($checked_status_to_be_displayed), $TODO->read());
    $todo_list_sorted           = $TODO->sortByKey($sort_key, $sort_order, $todo_list_picked_by_status);

    $view->assignTodoListToBeDisplayed($todo_list_sorted);