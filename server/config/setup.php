<?php

include_once './databaseClass.php';

try {
    $dbClass = new databaseClass();
    $connection = $dbClass->getSQLiteConnection();
    $path = __DIR__.'/../database/schema.sql';
    $exec = file_get_contents($path);
    $connection->exec($exec);
} catch(PDOExcetion $err) {
    echo "[Setup Error] ".$err->getMessage();
}