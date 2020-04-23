<?php
include_once "entity.php";

class User extends Entity {

    protected $table = "users";

    public $username;

    function create() {
        $stmt = $this->connection->prepare("INSERT INTO $this->table (username) VALUES (:uname)");
        $stmt->bindValue(':uname', $this->username, SQLITE3_TEXT);

        return $stmt->execute();
    }

    function read() {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE username=:uname");
        $stmt->bindValue(':uname', $this->username, SQLITE3_TEXT);

        return $stmt->execute();
    }

    function update() {

    }

    function delete() {

    }
}