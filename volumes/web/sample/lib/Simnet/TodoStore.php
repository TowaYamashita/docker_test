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
        SELECT * FROM todos ORDER BY created_at
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
        SELECT * FROM todos WHERE id = :id
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

        if($finished_at && $this->isValidFormatFinishedAt($finished_at)){
            // date_trunc('second', CURRENT_TIMESTAMP)
            // トランザクションの開始時刻の秒数以下を切り捨てる
            $sql = <<<SQL
            UPDATE todos
            SET title       = :title
            ,   finished_at = :finished_at
            ,   updated_at  = date_trunc('second', CURRENT_TIMESTAMP)
            WHERE id = :id
            SQL;

            $stmt = self::$DB->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':finished_at', $finished_at);
            $stmt->bindValue(':id', $id);
            $result = $this->executeQuery($stmt);
        }else{
            $sql = <<<SQL
            UPDATE todos
            SET title       = :title
            ,   finished_at = :finished_at
            ,   updated_at  = date_trunc('second', CURRENT_TIMESTAMP)
            WHERE id = :id
            SQL;

            $stmt = self::$DB->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':finished_at', $finished_at);
            $stmt->bindValue(':id', $id);
            $result = $this->executeQuery($stmt);
        }

        return $result;
    }

    /**
     * finish ToDo
     *
     * @param integer $id
     * @return bool
     */
    public function finish(int $id):bool{
        $result = true;

        if (empty($this->findById($id))) {
            return false;
        }

        // date_trunc('second', CURRENT_TIMESTAMP)
        // トランザクションの開始時刻の秒数以下を切り捨てる
        $sql = <<<SQL
        UPDATE todos
        SET finished_at = date_trunc('second', CURRENT_TIMESTAMP)
           , updated_at = date_trunc('second', CURRENT_TIMESTAMP)
        WHERE id = :id
        SQL;

        $stmt = self::$DB->prepare($sql);
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
}