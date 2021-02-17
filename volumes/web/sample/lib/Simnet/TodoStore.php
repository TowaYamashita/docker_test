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
     * @param string $todo
     * @return bool $result
     */
    public function create(string $title, string $deadline):bool{
        $result = true;

        $sql = <<<SQL
        INSERT INTO todos (title, finished_at) VALUES (:title, :deadline);
SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':deadline', $deadline);
        $result = $this->executeQuery($stmt);

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
        $finished_at = $todo["finished_at"] ?? "2099-12-31 11:59:59";

        $sql = <<<SQL
        UPDATE todos
        SET title       = :title
         ,  finished_at = :finished_at
         ,  updated_at  = CURRENT_TIMESTAMP(0)
        WHERE id = :id
SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':finished_at', $finished_at);
        $stmt->bindValue(':id', $id);
        $result = $this->executeQuery($stmt);

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

        $sql = <<<SQL
        UPDATE todos
        SET finished_at = CURRENT_TIMESTAMP(0)
           , updated_at = CURRENT_TIMESTAMP(0)
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
}
