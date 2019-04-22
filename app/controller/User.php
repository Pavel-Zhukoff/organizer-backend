<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 11.12.2018
 * Time: 18:55
 */

namespace App\Controller;


use Core\Classes\Controller;
use Core\Classes\Request;
use Core\Classes\Response;

class User extends Controller
{
    private $response;

    private $userModel;

    public function __construct()
    {
        $this->response = new Response();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Methods: *');
        $this->userModel = new \App\Models\User();
    }

    public function index(Request $request)
    {
        $data = $request->getData();
        if($request->getMethod() == "GET") {
            if (isset($data['data']['session']) && !empty($data['data']['session'])) {
                $user = $this->userModel->getUserBySession($data['data']['session']);
                if ($user['num_rows'] != 0) {
                    $this->response->display(json_encode(["answer" => $user['rows'], "code" => "200"]));
                }
                else {
                    $this->response->display(json_encode(["answer" => "Пользователь не найден!","code" => "404"]));
                }
            }
            else {
                $this->response->display(json_encode(["answer" => "Недостаточно аргументов!","code" => "401"]));
            }
        }
        else {
            $this->response->display(json_encode(["answer" => "Получен неверный ответ!","code" => "400"]));
        }
    }

    public function login(Request $request)
    {
        $data = $request->getData();
        if($request->getMethod() == "PUT") {
            if (isset($data['data']['email']) && !empty($data['data']['email']) &&
                    isset($data['data']['password']) && !empty($data['data']['password'])) {
                $user = $this->userModel->getUserByEmailAndPassword(strtolower($data['data']['email']),
                                            $data['data']['passwords']);
                if ($user['num_rows'] > 0) {
                    $user['rows']['login_time'] = time();
                    $user['rows']['login_ip'] = $_SERVER["REMOTE_ADDR"];
                    $user['rows']['session'] = md5($user['rows']['login_time'].
                            $user['rows']['login_ip'].
                            $user['rows']['email'].
                            $user['rows']['password']);
                    if ($this->userModel->updateUser($user['rows'])) {
                        $this->response->display(json_encode(["answer" => "Вы зарегистрировались!",
                            "data" => $this->userModel->getUserById($user['rows']['user_id'])['rows'],
                            "code" => "200"]));
                    }
                    else {
                        $this->response->display(json_encode(["answer" => "Что-то пошло не так!","code" => "500"]));
                    }
                }
                else {
                    $this->response->display(json_encode(["answer" => "Пользователь не найден!","code" => "404"]));
                }
            }
            else {
                $this->response->display(json_encode(["answer" => "Недостаточно аргументов!","code" => "401"]));
            }
        }
        else {
            $this->response->display(json_encode(["answer" => "Получен неверный ответ!","code" => "400"]));
        }
    }

    public function logout(Request $request)
    {
        $data = $request->getData();
        if($request->getMethod() == "PUT") {
            if (isset($data['data']['session']) && !empty($data['data']['session'])) {
                $user = $this->userModel->getUserBySession($data['data']['session']);
                if ($user['num_rows'] != 0) {
                    $user['rows']['session'] = 'false';
                    if ($this->userModel->updateUser($user['rows'])) {
                        $this->response->display(json_encode(["answer" => "Вы вышли!","code" => "200"]));
                    }
                    else {
                        $this->response->display(json_encode(["answer" => "Что-то пошло не так!","code" => "500"]));
                    }
                }
                else {
                    $this->response->display(json_encode(["answer" => "Пользователь не найден!","code" => "404"]));
                }
            }
            else {
                $this->response->display(json_encode(["answer" => "Недостаточно аргументов!","code" => "401"]));
            }
        }
        else {
            $this->response->display(json_encode(["answer" => "Получен неверный ответ!","code" => "400"]));
        }
    }

    public function new(Request $request)
    {
        $data = $request->getData();
        if($request->getMethod() == "POST") {
            if (isset($data['data']['email']) && isset($data['data']['password'])) {
                $user = $this->userModel->getUserByEmail(strtolower($data['data']['email']));
                if ($user['num_rows'] == 0) {
                    $user = array();
                    $user['email'] = strtolower($data['data']['email']);
                    $user['password'] = md5($user['email'].$data['data']['password'].$user['email']);
                    $user['name'] = !empty($data['data']['name'])?$data['data']['name']:"Пользователь #".time();
                    $user['login_time'] = time();
                    $user['register_date'] = time();
                    $user['login_ip'] = $_SERVER["REMOTE_ADDR"];
                    $user['session'] = md5($user['login_time'].$user['login_ip'].$user['email'].$user['password']);
                    if ($this->userModel->insertUser($user)) {
                        $this->response->display(json_encode(["answer" => "Вы зарегистрировались!",
                            "data" => $this->userModel->getUserByEmail($user['email']),
                            "code" => "200"]));
                    }
                    else {
                        $this->response->display(json_encode(["answer" => "Что-то пошло не так!", "code" => "403"]));
                    }
                }
                else {
                    $this->response->display(json_encode(["answer" => "Электронная почта уже используется!", "code" => "402"]));
                }
            }
            else {
                $this->response->display(json_encode(["answer" => "Недостаточно аргументов!", "code" => "401"]));
            }
        }
        else {
            $this->response->display(json_encode(["answer" => "Получен неверный ответ!", "code" => "400"]));
        }
    }

    
}