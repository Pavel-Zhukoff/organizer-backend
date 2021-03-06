<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 16.12.2018
 * Time: 19:46
 */

namespace core\db;


abstract class Adapter
{
    protected $connection;

    abstract public function __construct(array $config);

    abstract public function __destruct();

    abstract public function query(string $query) : array;

    abstract public function insert(string $table, array $data) : bool;

    abstract public function update(string $table, array $data, array $where) : bool;

    abstract public function escape(string $str) : string;

    abstract public function insert_id() : int;
}