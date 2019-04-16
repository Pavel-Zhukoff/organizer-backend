<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 14.12.2018
 * Time: 10:08
 */

namespace Core\Classes;


use Core\Utils\Config;

use Core\Db\MysqliAdapter;

class Database
{
    private static $dbConfig;
    private static $dbAdapter;
    private static $adapters = array(
        "mysqli", "mysql", "pdo"
    );


    public static function init()
    {
        self::$dbConfig = Config::load('config')['db'];
        self::loadAdapter();
    }

    private static function loadAdapter() : void
    {
        $adapter = strtolower(self::$dbConfig['adapter']);
        if (array_search($adapter, self::$adapters) !== false) {
            $adapterClass = 'Core\\Db\\'.ucfirst($adapter).'Adapter';
            self::$dbAdapter = new $adapterClass(self::$dbConfig[$adapter]);
        }
        else {
            self::$dbAdapter = new MysqliAdapter(self::$dbConfig['mysqli']);
        }
    }

    public static function query(string $query) : array
    {
        return self::$dbAdapter->query($query);
    }

    public static function escape(string $str) : string
    {
        return self::$dbAdapter->escape($str);
    }

    public static function insert_id() : string
    {
        return self::$dbAdapter->insert_id();
    }

    public static function insert(string $table, array $data) : bool
    {
        return self::$dbAdapter->insert($table, $data);
    }

    public static function update(string $table, array $data, array $where) : bool
    {
        return self::$dbAdapter->update($table, $data, $where);
    }
}