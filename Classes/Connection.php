<?php

class Connection
{
    const HOST = 'localhost';
    const DB_NAME = 'chat';
    const USERNAME = 'root';
    const PASSWORD = '';

    protected $connection;

    public function __construct()
    {
        $this->connection = new \PDO(
            'mysql:host=' . self::HOST . ';dbname=' .  self::DB_NAME,
            self::USERNAME,
            self::PASSWORD
        );
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function destroyConnection()
    {
        $this->connection = null;
    }
}
