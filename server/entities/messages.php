<?php

include_once "entity.php";

class Messages extends Entity {

    protected $table = "messages";

    public $sender;
    public $receiver;

    /**
     * @return SQLite3Result
     */
    function read() {
        $stmt = $this->connection->prepare("SELECT sender, receiver, body, date FROM $this->table WHERE sender=:sender AND receiver=:receiver UNION SELECT sender, receiver, body, date FROM messages WHERE sender=:receiver AND receiver=:sender ORDER BY date");
        $stmt->bindValue(':sender', $this->sender, SQLITE3_TEXT);
        $stmt->bindValue(':receiver', $this->receiver, SQLITE3_TEXT);

        return $stmt->execute();
    }
}