<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 14.12.2018
 * Time: 10:28
 */

namespace Core\Db;


class PdoAdapter extends Adapter
{

    public function __construct(array $config)
    {
        $this->connection = new \PDO('mysql:host='.$config['host'].
            ';dbname='.$config['name'].
            ';charset='.$config['charset'],
            $config['user'],
            $config['password']);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function __destruct()
    {
        unset($this->connection);
    }

    public function query(string $query) : array
    {
        $answer = array();
        $result = $this->connection->query($query);
        if ($result instanceof \PDOStatement) {
            $answer = array(
                'num_rows' => $result->num_rows,
                'rows' => $result->fetchAll()
            );
        }
        else {
            throw new \Exception("Ошибка выполнения запроса[{$this->connection->errorCode()}]:".
                " {$this->connection->errorInfo()}");
        }

        return $answer;
    }

    public function escape(string $str) : string
    {
        return $this->connection->quote($str);
    }

    public function insert_id() : int
    {
        return intval($this->connection->lastInsertId());
    }

    public function insert(string $table, array $data) : bool
    {
        // TODO: Implement insert() method.
    }

    public function update(string $table, array $data, array $where) : bool
    {
        // TODO: Implement update() method.
    }
}