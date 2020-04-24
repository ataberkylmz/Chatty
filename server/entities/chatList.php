<?php

include_once "entity.php";

class ChatList extends Entity {

    protected $table = "messages";

    public $sender;

    /**
     * @return SQLite3Result
     */
    function read() {

        $stmt = $this->connection->prepare("SELECT DISTINCT receiver FROM messages WHERE sender=:sender UNION SELECT DISTINCT sender FROM messages WHERE receiver=:sender");
        $stmt->bindValue(':sender', $this->sender, SQLITE3_TEXT);

        return $stmt->execute();
    }
}