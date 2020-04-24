<?php

class databaseClass {

    /**
     * @var SQLite3
     */
    public $connection;

    /**
     * @return SQLite3
     */
    public function getSQLiteConnection() {
        try{
            $path = __DIR__ . '/../database/database.db';
            $this->connection = new SQLite3($path);
            $this->connection->exec('PRAGMA foreign_keys = ON;');
            return $this->connection;
        } catch (SQLiteException $err) {
            echo "[DBClass Error] ".$err->getMessage();
        }
    }
}
