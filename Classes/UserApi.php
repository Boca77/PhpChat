<?php

class UserApi
{
    public $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
        
    }
    public function getUsers()
    {

        $query = "SELECT id, name, surname, email  FROM users";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
