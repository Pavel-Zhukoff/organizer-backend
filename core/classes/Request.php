<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 11.12.2018
 * Time: 17:22
 */

namespace Core\Classes;

class Request
{
    private $data;

    private $method;

    public function __construct()
    {
        if (!isset($_REQUEST)) {
            throw new \Exception("Отсутствует POST или GET запрос!");
        }
        $this->prepareData();
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        unset($_REQUEST, $_GET, $_POST);
    }

    public function __destruct()
    {
        unset($this->data);
    }

    private function prepareData()
    {
        $this->data = array(
            'files'  => isset($_FILES)?$_FILES:null,
            'data'   => array()
        );
        $buf = json_decode(file_get_contents('php://input', 'r'), true);
        foreach ($buf as $key => $value)
           $this->data['data'][strtolower($key)] = trim($value);

    }

    public function getData() : array
    {
        return $this->data;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

}