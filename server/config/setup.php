<?php

include_once '../database/databaseClass.php';

/**
 * Setup script to create a new database from SQL schema.
 */
try {
    $dbClass = new databaseClass();
    $connection = $dbClass->getSQLiteConnection();
    $path = __DIR__.'/../database/schema.sql';
    $exec = file_get_contents($path);
    // No need to check for SQL injections, I guess it is kinda pointless at this stage?
    $connection->exec($exec);
} catch(SQLiteException $err) {
    echo "[Setup Error] ".$err->getMessage();
}