<?php

namespace Simnet;

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Database
{
    private static $pdo;

    public static function init()
    {
        $dsn      = "pgsql:dbname={$_ENV['DBNAME']} host={$_ENV['HOST']} port={$_ENV['PORT']}";
        $user     = $_ENV['DBUSER'];
        $password = $_ENV['PASSWORD'];

        try
        {
            self::$pdo = new \PDO($dsn, $user, $password);
        }catch (PDOException $e)
        {
            print('Error:'.$e->getMessage());
            die();
        }
    }

    /**
     * return PDO_instance
     *
     * @return PDO
     */
    public static function getPDO()
    {
        if(!isset(self::$pdo))
        {
            self::init();
        }
        return self::$pdo;
    }
}
