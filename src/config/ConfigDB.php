<?php

namespace Config;

class ConfigDB
{
 
    private static function getLocal()
    {
        $IS_LOCAL = ( str_contains( $_SERVER['SERVER_ADDR'], '::1' ) || str_contains( $_SERVER['SERVER_ADDR'], 'localhost' ) || str_contains( $_SERVER['HTTP_HOST'], 'http://127.0.0.1/' )  );
        return $IS_LOCAL;
    }

    public static function getDB_HOST()
    {
        $DB_HOST = (  ConfigDB::getLocal() ) ? $_ENV['DB_HOST'] : $_ENV['SDB_HOST'];
        return $DB_HOST;
    }

    public static function getDB_USER()
    {
        $DB_USER = (  ConfigDB::getLocal() ) ? $_ENV['DB_USER'] : $_ENV['SDB_USER'];
        return $DB_USER;
    }

    public static function getDB_PASSWORD()
    {
        $DB_PASSWORD = (  ConfigDB::getLocal() ) ? $_ENV['DB_PASS'] : $_ENV['SDB_PASS'];
        return $DB_PASSWORD;
    }

    public static function getDB_NAME()
    {
        $DB_NAME = (  ConfigDB::getLocal() ) ? $_ENV['DB_NAME'] : $_ENV['SDB_NAME'];
        return $DB_NAME;
    }

}
