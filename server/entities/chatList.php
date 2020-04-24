<?php

include_once "entity.php";

class ChatList extends Entity {

    protected $table = "messages";

    public $sender;

    function read() {
        $stmt = $this->connection->prepare("SELECT DISTINCT receiver FROM messages WHERE sender=:sender");
        $stmt->bindValue(':sender', $this->sender, SQLITE3_TEXT);

        return $stmt->execute();
    }
}