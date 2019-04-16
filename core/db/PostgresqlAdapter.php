<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 14.12.2018
 * Time: 10:28
 */

namespace Core\Db;


class PostgresqlAdapter extends Adapter
{

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    public function query(string $query): array
    {
        // TODO: Implement query() method.
    }

    public function escape(string $str): string
    {
        // TODO: Implement escape() method.
    }

    public function insert_id(): string
    {
        // TODO: Implement insert_id() method.
    }
}