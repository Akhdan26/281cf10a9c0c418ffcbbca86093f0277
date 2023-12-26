<?php

namespace Src\Config;

class Database
{
    private static $conn;

    public static function getConnection()
    {
        // $host = $_ENV['DB_HOST'];
        // $port = $_ENV['DB_PORT'];
        // $db   = $_ENV['DB_NAME'];
        // $user = $_ENV['DB_USERNAME'];
        // $pass = $_ENV['DB_PASSWORD'];

        // for docker
        $host = getenv('PGHOST');
        $db   = getenv('PGDATABASE');
        $user = getenv('PGUSER');
        $pass = getenv('PGPASSWORD');

        if (!self::$conn) {
            // self::$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");
            self::$conn = pg_connect("host=$host dbname=$db user=$user password=$pass");
        }

        if (!self::$conn) {
            echo 'DB Connect error!';
            exit;
        }

        return self::$conn;
    }
}
