<?php


namespace app\models;

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

    public function getUserBySession(string $session) : array
    {
        $session = Database::escape($session);
        return Database::query("SELECT * FROM `user` WHERE `session` = '{$session}'");
    }

    public function updateUser(array $user) : bool
    {
        //
    }
}