<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class TodoListManager
{
    /** @var PDO $DB */
    private static $DB;
    private const TABLE_NAME = 'todos';

    public function __construct()
    {
        self::$DB = \Simnet\Database::getPDO();
    }

    /**
     * create Todo
     *
     * @param string $todo
     * @return bool $result
     */
    public function create(string $todo):bool
    {
        $result = true;

        $sql = <<<SQL
        INSERT INTO todos (title) VALUES (:todo);
SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->bindValue(':todo', $todo);
        try
        {
            self::$DB->beginTransaction();
            $stmt->execute();
            self::$DB->commit();
        } catch (Throwable $e)
        {
            self::$DB->rollBack();
            $result = false;
        }

        return $result;
    }

    /**
     * read ToDo
     *
     * @return array
     */
    public function read():array
    {
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
     * @return bool
     */
    public function update(int $id):bool
    {
        $result = true;

        if (empty($this->findById($id))) {
            return false;
        }

        $sql = <<<SQL
        UPDATE todos
        SET finished_at = CURRENT_TIMESTAMP
           , updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
SQL;

        $stmt = self::$DB->prepare($sql);
        $stmt->bindValue(':id', $id);
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
