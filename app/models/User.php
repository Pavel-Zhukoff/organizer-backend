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
        return Database::query("SELECT * FROM `user` WHERE `user_id` = {$id}");
    }

    public function getUserByEmail(string $email) : array
    {
        $email = Database::escape($email);
        return Database::query("SELECT * FROM `user` WHERE `email` = '{$email}'");
    }

    public function getUserByEmailAndPassword(string $email, string $password) : array
    {
        $email = Database::escape($email);
        $password = md5($email.Database::escape($password).$email);
        return Database::query("SELECT * FROM `user` WHERE `email` = '{$email}' AND `password` = '{$password}'");
    }

    public function getUserBySession(string $session) : array
    {
        $session = Database::escape($session);
        return Database::query("SELECT * FROM `user` WHERE `session` = '{$session}'");
    }

    public function updateUser(array $user) : bool
    {
        return Database::update('user', [
            "login_time" => $user["login_time"],
            "login_ip"   => $user["login_ip"],
            "session"    => $user["session"]
        ], ["user_id" => $user["user_id"]]);
    }

    public function insertUser(array $user) : bool
    {
        return Database::insert('user', $user);
    }
}