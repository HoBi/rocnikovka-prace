<?php

class Db
{

    private static $connection;

    private static $settings = array
    (
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false
    );

    public static function logIn($host, $user, $password, $database)
    {
        if (!isset(self::$connection))
        {
            self::$connection = @new PDO
            (
                "mysql:host=$host;dbname=$database",
//                "mysql:unix_socket=/tmp/mysql51.sock;dbname=$database",
                $user,
                $password,
                self::$settings
            );
        }
    }

    public static function queryOne($query, $parameters = array())
    {
        $result = self::$connection->prepare($query);
        $result->execute($parameters);
        return $result->fetch();
    }

    public static function queryAll($query, $parameters = array())
    {
        $result = self::$connection->prepare($query);
        $result->execute($parameters);
        return $result->fetchAll();
    }

    public static function queryAlone($query, $parameters = array())
    {
        $result = self::queryOne($query, $parameters);
        return $result[0];
    }

    public static function query($query, $parameters = array())
    {
        $result = self::$connection->prepare($query);
        $result->execute($parameters);
        return $result->rowCount();
    }

    public static function insert($table, $parameters = array()) {
        return self::query("INSERT INTO `$table` (`".
            implode('`, `', array_keys($parameters)).
            "`) VALUES (".str_repeat('?,', sizeOf($parameters)-1)."?)",
            array_values($parameters));
    }

    public static function update($table, $values = array(), $condition, $parameters = array()) {
        return self::query("UPDATE `$table` SET `".
            implode('` = ?, `', array_keys($values)).
            "` = ? " . $condition,
            array_merge(array_values($values), $parameters));
    }

    public static function getLastId()
    {
        return self::$connection->lastInsertId();
    }
}