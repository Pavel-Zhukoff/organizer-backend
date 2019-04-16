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
    private $validData;

    public function __construct()
    {

        if (!isset($_REQUEST)) {
            throw new \Exception("Отсутствует POST или GET запрос!");
        }
        $this->prepareData();
        $this->validateData();
        unset($_REQUEST, $_GET, $_POST);
    }

    public function __destruct()
    {
        unset($this->data);
    }

    private function prepareData()
    {
        $this->data = array(
            'method' => strtolower($_SERVER['REQUEST_METHOD']),
            'files'  => isset($_FILES)?$_FILES:null
        );
        $buf = $_REQUEST;
        foreach ($buf as $key => $value)
            $this->data['data'][strtolower($key)] = trim($buf[$key]);
    }
    
    private function validateData()
    {
        $this->validData = array(
            'method' => strtolower($_SERVER['REQUEST_METHOD']),
            'files'  => isset($_FILES)?$_FILES:null
        );
        $buf = $_REQUEST;
        foreach ($buf as $key => $value)
            $this->validData['data'][strtolower($key)] = filter_var(trim($buf[$key]), FILTER_SANITIZE_STRING);
    }
    
    public function getRawData() : array
    {
        return $this->data;
    }

    public function getData() : array
    {
        return $this->validData;
    }
    

    
}