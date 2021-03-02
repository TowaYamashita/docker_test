<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class TodoStore
{
    /** @var PDO $DB */
    private static $DB;

    public function __construct(){
        self::$DB = \Simnet\Database::getPDO();
    }

    /**
     * create Todo
     *
     * @param array $todo
     * @return bool $result
     */
    public function create(array $todo):bool{
        $result = true;

        $title       = $todo["title"] ?? "NO TITLE";
        $finished_at = $todo["finished_at"] ?? "";

        if($finished_at && $this->isValidFormatFinishedAt($finished_at)){
            $sql = <<<SQL
            INSERT INTO todos (title, finished_at) VALUES (:title, :finished_at);
            SQL;

            $stmt = self::$DB->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':finished_at', $finished_at);
            $result = $this->executeQuery($stmt);
        }else{
            $sql = <<<SQL
            INSERT INTO todos (title) VALUES (:title);
            SQL;

            $stmt = self::$DB->prepare($sql);
            $stmt->bindValue(':title', $title);
            $result = $this->executeQuery($stmt);
        }

        return $result;
    }

    /**
     * read ToDo
     *
     * @return array
     */
    public function read():array{
        $sql = <<<SQL
        SELECT
        todos.id, todos.title, todo_statuses.status, todos.finished_at, todos.created_at, todos.updated_at
        FROM todos
        JOIN todo_statuses
        ON todo_statuses.id = todos.status_id
        ORDER BY created_at
        SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll() ?: [] ;
    }

    /**
     * find row by id
     *
     * @param integer $id
     * @return array
     */
    public function findById(int $id):array {
        $sql = <<<SQL
        SELECT
        todos.id, todos.title, todo_statuses.status, todos.finished_at, todos.created_at, todos.updated_at
        FROM todos
        JOIN todo_statuses
        ON todo_statuses.id = todos.status_id
        WHERE todos.id = :id
        SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch() ?: [] ;
    }

    /**
     * update ToDo
     *
     * @param integer $id
     * @param array $todo
     * @return bool
     */
    public function update(int $id, array $todo):bool{
        $result = true;

        if (empty($this->findById($id))) {
            return false;
        }

        $title       = $todo["title"] ?? "NO TITLE";
        $finished_at = $todo["finished_at"] ?? "";
        $status      = $todo["status"] ?? "todo";

        if($finished_at && $this->isValidFormatFinishedAt($finished_at)){
            // date_trunc('second', CURRENT_TIMESTAMP)
            // トランザクションの開始時刻の秒数以下を切り捨てる
            $sql = <<<SQL
            UPDATE todos
            SET title       = :title
            ,   finished_at = :finished_at
            ,   updated_at  = date_trunc('second', CURRENT_TIMESTAMP)
            ,   status_id   = (
                SELECT id
                FROM todo_statuses
                WHERE status = :status
            )
            WHERE id = :id
            SQL;

            $stmt = self::$DB->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':finished_at', $finished_at);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id);
            $result = $this->executeQuery($stmt);
        }else{
            $sql = <<<SQL
            UPDATE todos
            SET title       = :title
            ,   updated_at  = date_trunc('second', CURRENT_TIMESTAMP)
            ,   status_id   = (
                SELECT id
                FROM todo_statuses
                WHERE status = :status
            )
            WHERE id = :id
            SQL;

            $stmt = self::$DB->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':id', $id);
            $result = $this->executeQuery($stmt);
        }

        return $result;
    }

    /**
     * change ToDo status
     *
     * @param integer $id
     * @return bool
     */
    public function changeTodoStatus(int $id):bool{
        $result = true;

        if (empty($todo = $this->findById($id))) {
            return false;
        }

        $current_status = $todo['status'];
        $next_status    = (new \Simnet\TodoStatus($current_status))->getNextStatus();

        // date_trunc('second', CURRENT_TIMESTAMP)
        // トランザクションの開始時刻の秒数以下を切り捨てる
        $sql = <<<SQL
        UPDATE todos
        SET  updated_at = date_trunc('second', CURRENT_TIMESTAMP)
           , status_id   = (
                SELECT id
                FROM todo_statuses
                WHERE status = :status
            )
        WHERE id = :id
        SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->bindValue(':status', $next_status);
        $stmt->bindValue(':id', $id);
        $result = $this->executeQuery($stmt);

        return $result;
    }

    /**
     * delete ToDo
     *
     * @param integer $id
     * @return bool
     */
    public function delete(int $id):bool{
        $result = true;

        if (empty($this->findById($id))) {
            return false;
        }

        $sql = <<<SQL
        DELETE FROM todos
        WHERE id = :id
        SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->bindValue(':id', $id);
        $result = $this->executeQuery($stmt);

        return $result;
    }

    /**
     * sort ToDo
     *
     * @param string $targeted_key
     * @param string $order
     * @param array $todo_array
     * @return array $todo_array
     */
    public function sortByKey(string $targeted_key, string $order, array $todo_array):array{
        $db_column = $this->getAllColumnsFromTodos();

        if($todo_array && in_array($targeted_key, $db_column, true)){
            $target_array = [];
            foreach ($todo_array as $key => $value) {
                $target_array[$key] = $value[$targeted_key];
            }
            if($order === "asc"){
                array_multisort($target_array, SORT_ASC, $todo_array);
            }elseif($order === "desc"){
                array_multisort($target_array, SORT_DESC, $todo_array);
            }
        }
        return $todo_array;
    }

    /**
     * pick todo by checked status
     *
     * @param array $checked_status_to_be_displayed nullable
     * @param array $todo_list
     * @return array
     */
    public function pickByCheckedStatus(?array $checked_status_to_be_displayed, array $todo_list):array{
        if(is_null($checked_status_to_be_displayed)){
            return $todo_list;
        }

        $checked_status_to_be_displayed = array_values(array_intersect($checked_status_to_be_displayed, ['todo', 'doing', 'done']));
        $todo_list_picked_by_status = [];
        foreach($todo_list as $todo){
            if(in_array($todo["status"], $checked_status_to_be_displayed, true)){
                $todo_list_picked_by_status[] = $todo;
            }
        }

        return $todo_list_picked_by_status;

    }

    /**
     * execute prepared_Query
     *
     * @param object $stmt
     * @return bool
     */
    private function executeQuery(object $stmt):bool{
        $result = true;
        try {
            self::$DB->beginTransaction();
            $stmt->execute();
            self::$DB->commit();
        } catch (Throwable $e) {
            self::$DB->rollBack();
            $result = false;
        }
        return $result;
    }

    /**
     * check format finished_at
     *
     * @param string $finished_at
     * @return bool
     */
    private function isValidFormatFinishedAt(string $finished_at):bool{
        // e.g. 2020-03-04T09:30
        $pattern = "/\A[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])T([01][0-9]|2[0-3]):[0-5][0-9]\z/";

        $result = preg_match($pattern, $finished_at);

        return ($result === 1) ? true : false;
    }

    /**
     *  get column about todos(table)
     *
     * @return array
     */
    private function getAllColumnsFromTodos():array{
        $sql = <<< SQL
        select column_name
        from information_schema.columns
        where table_name = 'todos'
        SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [] ;
    }
}
