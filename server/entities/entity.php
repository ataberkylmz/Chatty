<?php

/**
 * Class Entity, super class of all entities, such as user, message, etc...
 */
class Entity {

    /**
     * @var SQLite3
     */
    protected $connection;

    /**
     * @var Table name of entity.
     */
    protected $table;

    public $id;

    /**
     * Entity constructor.
     * @param $conn SQLite3
     */
    function __construct($conn)
    {
        $this->connection = $conn;
    }


}