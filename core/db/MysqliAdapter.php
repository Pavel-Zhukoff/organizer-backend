<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 14.12.2018
 * Time: 10:26
 */

namespace Core\Db;


class MysqliAdapter extends Adapter
{

    public function __construct(array $config)
    {
        $this->connection = new \mysqli($config['host'],
            $config['user'],
            $config['password'],
            $config['name'],
            $config['port']);
        if ($this->connection->connect_errno !== 0) {
            throw new \Exception("Ошибка подключения[{$this->connection->connect_errno}]:".
                " {$this->connection->connect_error}");
        }
    }

    public function __destruct()
    {
        $this->connection->close();
        unset($this->connection);
    }

    public function query(string $query) : array
    {
        $answer = array();
        $result = $this->connection->query($query);
        if ($result !== false) {
            if ($result instanceof \mysqli_result) {
                $answer = array(
                    'num_rows' => $result->num_rows,
                    'rows' => array()
                );
                while($row = $result->fetch_assoc())
                    $answer['rows'][] = $row;
                $result->free();
            }
            else {
                $answer = array(
                    "insert_id" =>  $this->connection->insert_id
                );
            }
        }
        else {
            throw new \Exception("Ошибка выполнения запроса[{$this->connection->errno}]:".
                " {$this->connection->error}");
        }

        return $answer;
    }

    public function escape(string $str) : string
    {
        return $this->connection->real_escape_string($str);
    }

    public function insert_id() : integer
    {
        return intval($this->connection->insert_id);
    }
}