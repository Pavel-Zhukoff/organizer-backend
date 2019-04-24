<?php


namespace App\Controller;


use Core\Classes\Controller;
use Core\Classes\Request;
use Core\Classes\Response;

class Note extends Controller
{
    private $response;

    private $noteModel;

    public function __construct()
    {
        $this->response = new Response();
        $this->response->addHeader('Content-Type: application/json');
        $this->response->addHeader('Access-Control-Allow-Headers: *');
        $this->response->addHeader('Access-Control-Allow-Origin: *');
        $this->response->addHeader('Access-Control-Allow-Methods: *');
        $this->noteModel = new \App\Models\Note();
    }

    public function index(Request $request)
    {
        $data = $request->getData();
        if($request->getMethod() == "POST") {
            if (isset($data['data']['user_id']) && !empty($data['data']['user_id'])) {
                $notes = $this->noteModel->getNotesByUserId($data['data']['user_id']);
                if ($notes['num_rows'] != 0) {
                    $this->response->display(json_encode(["answer" => $notes['rows'], "code" => "200"]));
                }
                else {
                    $this->response->display(json_encode(["answer" => "Заметок нет!","code" => "404"]));
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
            if (isset($data['data']['text']) && !empty($data['data']['text']) &&
                isset($data['data']['user_id']) && !empty($data['data']['user_id'])) {
                $note['creation_date'] = time();
                $note['title'] = (isset($data['data']['title']) && !empty($data['data']['title']))?
                    $data['data']['title'] : 'Заметка #'.time();
                $note['subtitle'] = (isset($data['data']['subtitle']) && !empty($data['data']['subtitle']))?
                    $data['data']['subtitle'] : '';
                $note['text'] = $data['data']['text'];
                $note['user_id'] = $data['data']['user_id'];
                if ($this->noteModel->insertNote($note)) {
                    $this->response->display(json_encode(["answer" => "Заметка добавлена!", "code" => "200"]));
                }
                else {
                    $this->response->display(json_encode(["answer" => "Что-то пошло не так!", "code" => "403"]));
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

    public function delete(Request $request)
    {
        $data = $request->getData();
        //throw new \Exception(json_encode($data));
        if($request->getMethod() == "POST") {
            if (isset($data['data']['id']) && !empty($data['data']['id'])) {
                if ($this->noteModel->deleteNote($data['data']['id'])) {
                    $this->response->display(json_encode(["answer" => "Заметка удалена!", "code" => "200"]));
                }
                else {
                    $this->response->display(json_encode(["answer" => "Что-то пошло не так!", "code" => "403"]));
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

    public function update(Request $request)
    {
        $data = $request->getData();
        if($request->getMethod() == "POST") {
            if (isset($data['data']['id']) && !empty($data['data']['id']) &&
                isset($data['data']['user_id']) && !empty($data['data']['user_id'])) {
                $noteOld = $this->noteModel->getNoteById($data['data']['id']);
                $note['title'] = (isset($data['data']['title']) && !empty($data['data']['title']))?
                    $data['data']['title'] : $noteOld['rows'][0]['title'];
                $note['subtitle'] = (isset($data['data']['subtitle']) && !empty($data['data']['subtitle']))?
                    $data['data']['subtitle'] : $noteOld['rows'][0]['subtitle'];
                $note['text'] = (isset($data['data']['text']) && !empty($data['data']['text']))?
                    $data['data']['text'] : $noteOld['rows'][0]['text'];
                $note['note_id'] = $data['data']['id'];
                if ($this->noteModel->updateNote($note)) {
                    $this->response->display(json_encode(["answer" => "Заметка обновлена!", "code" => "200"]));
                }
                else {
                    $this->response->display(json_encode(["answer" => "Что-то пошло не так!", "code" => "403"]));
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
}