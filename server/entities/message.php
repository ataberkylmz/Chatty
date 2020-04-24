<?php

include_once "entity.php";

class Message extends Entity {

    protected $table = "messages";

    public $sender;
    public $receiver;
    public $body;

    /**
     * @return SQLite3Result
     */
    function create() {
        $stmt = $this->connection->prepare("INSERT INTO $this->table (sender, receiver, body, date) VALUES (:sender, :receiver, :body, datetime('now'))");
        $stmt->bindValue(':sender', $this->sender, SQLITE3_TEXT);
        $stmt->bindValue(':receiver', $this->receiver, SQLITE3_TEXT);
        $stmt->bindValue(':body', $this->body, SQLITE3_TEXT);

        return $stmt->execute();
    }

    /**
     * @return SQLite3Result
     */
    function read() {
        $stmt = $this->connection->prepare("SELECT * FROM $this->table WHERE id=:id");
        $stmt->bindValue(':id', $this->id, SQLITE3_INTEGER);

        return $stmt->execute();
    }
}