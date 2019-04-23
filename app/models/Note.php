<?php


namespace App\Models;


use Core\Classes\Database;

class Note
{
    public function getNotesByUserId(string $user_id) : array
    {
        $user_id = Database::escape($user_id);
        return Database::query("SELECT * FROM `note` WHERE `user_id` = '{$user_id}' AND `is_archived` = 0 ORDER BY `creation_date`");
    }

    public function getNoteById(string $id) : array
    {
        $id = Database::escape($id);
        return Database::query("SELECT * FROM `note` WHERE `note_id` = '{$id}'");
    }

    public function deleteNote(string $id) : bool
    {
        $id = Database::escape($id);
        return Database::update('note', [
            "is_archived" => 1
        ], ["note_id" => $id]);
    }

    public function updateNote(array $note) : bool
    {
        return Database::update('note', [
            "title"    => $note["title"],
            "subtitle" => $note["subtitle"],
            "text"     => $note["text"]
        ], ["note_id" => $note["note_id"]]);
    }

    public function insertNote(array $note) : bool
    {
        return Database::insert('note', $note);
    }
}