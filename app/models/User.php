<?php


namespace App\Models;

use Core\Classes\Database;

class User
{
    public function getUsers() : array
    {
        return Database::query("SELECT * FROM `user` ORDER BY `register_date`");
    }

    public function getUserById(string $id) : array
    {
        $id = Database::escape($id);
        $user = Database::query("SELECT * FROM `user` WHERE `user_id` = {$id}");
        $data = array();
        if ($user['num_rows'] != 0) {
            $data = array(
                'email' => $user['data']['email'],
                'name' => $user['data']['name'],
                'session' => $user['data']['session'],
                'user_id' => $user['data']['user_id'],
            );
        }
        return $data;
    }

    public function getUserByEmail(string $email) : array
    {
        $email = Database::escape($email);
        $user = Database::query("SELECT * FROM `user` WHERE `email` = '{$email}'");
        $data = array();
        if ($user['num_rows'] != 0) {
            $data = array(
                'email' => $user['data']['email'],
                'name' => $user['data']['name'],
                'session' => $user['data']['session'],
                'user_id' => $user['data']['user_id'],
            );
        }
        return $data;
    }

    public function getUserByEmailAndPassword(string $email, string $password) : array
    {
        $email = Database::escape($email);
        $password = md5($email.Database::escape($password).$email);
        $user = Database::query("SELECT * FROM `user` WHERE `email` = '{$email}' AND `password` = '{$password}'");
        $data = array();
        if ($user['num_rows'] != 0) {
            $data = array(
                'email' => $user['data']['email'],
                'name' => $user['data']['name'],
                'session' => $user['data']['session'],
                'user_id' => $user['data']['user_id'],
            );
        }
        return $data;
    }

    public function getUserBySession(string $session) : array
    {
        $session = Database::escape($session);
        $user = Database::query("SELECT * FROM `user` WHERE `session` = '{$session}'");
        $data = array();
        if ($user['num_rows'] != 0) {
            $data = array(
                'email' => $user['data']['email'],
                'name' => $user['data']['name'],
                'session' => $user['data']['session'],
                'user_id' => $user['data']['user_id'],
            );
        }
        return $data;
    }

    public function updateUser(array $user) : bool
    {
        return Database::update('user', [
            "login_time" => $user["login_time"],
            "login_ip" => $user["login_ip"],
            "session" => $user["session"]
        ], ["user_id" => $user["user_id"]]);
    }

    public function insertUser(array $user) : bool
    {
        return Database::insert('user', $user);
    }
}