<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 11.12.2018
 * Time: 17:22
 */

namespace Core\Classes;


use function PHPSTORM_META\type;

class Request
{
    private $data;

    private $method;

    public function __construct()
    {
        $this->response = new Response();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Methods: *');
        if (!isset($_REQUEST)) {
            throw new \Exception("Отсутствует POST или GET запрос!");
        }
        $this->prepareData();
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new \Exception("Неожиданный метод запроса!");
            }
        }
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
        $buf = json_decode(array_keys($_REQUEST)[0], true);
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