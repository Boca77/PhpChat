<?php
include_once 'Classes/Connection.php';
class User extends Connection
{
    private $id;
    public function __construct($id = 'null')
    {
        parent::__construct();
        $this->id = $id;
    }

    public function getUser()
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserId($email)
    {
        $stmt = $this->connection->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function setUser($credentials)
    {
        $stmt = $this->connection->prepare('INSERT INTO users (name, surname, email, password) VALUES ( :name, :surname, :email, :password)');
        $stmt->bindParam(':name', $credentials['name']);
        $stmt->bindParam(':surname', $credentials['surname']);
        $stmt->bindParam(':email', $credentials['email']);
        $stmt->bindParam(':password', $credentials['password']);
        $stmt->execute();
    }

    public function destroyUser()
    {
        $this->connection = null;
    }
}
