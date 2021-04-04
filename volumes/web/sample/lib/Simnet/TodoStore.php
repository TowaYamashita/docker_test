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

        $title       = $todo["title"];
        $finished_at = $todo["finished_at"];

        if(isset($finished_at)){
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

        $title       = $todo["title"];
        $finished_at = $todo["finished_at"];
        $status      = $todo["status"];

        if(isset($finished_at)){
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
        $todo_status    = new \Simnet\Todo\Status($current_status);
        $todo_status->advance();
        $next_status    = $todo_status->get();

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
     * @param array $checked_status_to_be_displayed
     * @param array $todo_array
     * @return array
     */
    public function pickTodoByCheckedStatus(array $checked_status_to_be_displayed, array $todo_array):array{
        $todo_list_picked_by_status = [];
        foreach($todo_array as $todo){
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
