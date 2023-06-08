<?php

namespace app\lib;

use PDO;

class Db
{
    private static $pdo = null;
    private static $dsn = null;
    private static $user = 'root';
    private static $password = '';
    private static $db = 'auth';
    private static $host = '127.0.0.1';

    public static function getInstanse()
    {
        if (self::$pdo == null) {
            self::$dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$db;

            self::$pdo = new PDO(
                self::$dsn,
                self::$user,
                self::$password
            );
        }

        return self::$pdo;
    }


    private function __construct()
    {
    }
    private function __clone()
    {
    }
}
