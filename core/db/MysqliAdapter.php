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
                throw new \Exception("Ошибка выполнения запроса!");
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

    public function insert_id() : int
    {
        return intval($this->connection->insert_id);
    }

    public function insert(string $table, array $data) : bool
    {
        $table = strtolower($this->escape($table));
        $attrs = array();
        $values = array();
        foreach ($data as $key => $value) {
            $key = strtolower($this->escape($key));
            $value = $this->escape($value);
            $attrs[] = '`'.$key.'`';
            $values[] = '"'.$value.'"';
        }
        $fields = implode(', ',$attrs);
        $values = implode(', ',$values);
        $query = "INSERT INTO {$table} ({$fields}) VALUES ({$values})";
        $result = $this->connection->query($query);
        if ($result !== false) {
            return true;
        }
        else {
            throw new \Exception("Ошибка выполнения запроса[{$this->connection->errno}]:".
                " {$this->connection->error}");
        }
    }

    public function update(string $table, array $data, array $where) : bool
    {
        $table = strtolower($this->escape($table));
        $values = array();
        $where_query = array();
        $query = "UPDATE {$table} SET ";
        foreach ($data as $key => $value) {
            $key = strtolower($this->escape($key));
            $value = $this->escape($value);
            $values[] = '`'.$key.'` = "'.$value.'"';
        }
        $query .= implode(', ', $values).' WHERE ';
        foreach ($where as $key => $value) {
            $key = strtolower($this->escape($key));
            $value = $this->escape($value);
            $where_query[] = '`'.$key.'` = "'.$value.'"';
        }
        if (count($where_query) > 1) {
            $query .= implode(' AND ', $where_query);
        } else {
            $query .= $where_query[0];
        }
        $result = $this->connection->query($query);
        if ($result !== false) {
            return true;
        }
        else {
            throw new \Exception("Ошибка выполнения запроса[{$this->connection->errno}]:".
                " {$this->connection->error}");
        }
    }
}