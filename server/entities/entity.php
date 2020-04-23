<?php

class Entity {

    protected $connection;
    protected $table;

    public $id;

    function __construct($conn)
    {
        $this->connection = $conn;
    }


}