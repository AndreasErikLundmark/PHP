<?php
//a pool of connections to database
//https://www.php.net/manual/en/pdo.connections.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class dbConnectionPool
{
    private static $connectionPool = null;

    private static $pool = []; // creating an array for connections

    private static $poolSize = 8; // setting a maximum number of connections

    private function __construct()
    {
        echo "connection pool created";
    }

//Creates the pool when called
    public static function getInstance()
    {
//        This ensures only one pool at the time can be created
        if (self::$connectionPool === null) {
            self::$connectionPool = new dbConnectionPool();
        }
        //return the new one or already existing one.
        return self::$connectionPool;
    }

    public function getConnection()
    {
        if (count(self::$pool) === self::$poolSize) {

            echo 'Connections busy, please try again';
            exit();
        }

        if (empty(self::$pool)) {
            $dsn = 'mysql:host=localhost;dbname=databasename';
            $username = "root";
            $password = "";

            $options = array(
//                Persistent connections are not closed at the end of the script, Request a persistent connection, rather than creating a new connection
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            try {
                $connection = new PDO($dsn, $username, $password, $options);
                self::$pool[] = $connection;
            } catch (PDOException $e) {
//                throw new Exception('Error: ' . $e->getMessage());
                echo 'nope';
            }
        } else {
            return array_pop(self::$pool);
        }

        return $connection;
    }

    public function returnConnection($connection)
    {
        self::$pool[] = $connection;
    }
}
?>
