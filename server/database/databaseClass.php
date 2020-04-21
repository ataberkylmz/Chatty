<?php

class databaseClass {
    public $connection;

    public function getSQLiteConnection() {
        try{
            $path = __DIR__ . '/../database/database.db';
            $this->connection = new PDO('sqlite:'.$path);
            $this->connection->exec('PRAGMA foreign_keys = ON;');
            return $this->connection;
        } catch (PDOException $err) {
            echo "[DBClass Error] ".$err->getMessage();
        }
    }
}
