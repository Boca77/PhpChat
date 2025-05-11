<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return header('Location: index.php?errorLogin=Invalid request');
}

include_once 'Classes/Connection.php';

session_start();

$_SESSION['old'] = $_POST;

if ($_POST['email'] == '') {
    return header('Location: index.php?errorLogin=Please enter email');
}

if ($_POST['password'] == '') {
    return header('Location: index.php?errorLogin=Please enter password');
}

$dbConnection = new Connection();
$connection = $dbConnection->getConnection();

$stmt = $connection->prepare('SELECT * FROM users WHERE email = :email');
$stmt->bindParam(':email', $_POST['email']);
$stmt->execute();
$credentials = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$credentials) {
    return header('Location: index.php?errorLogin=Invalid email');
}

var_dump($credentials);

if (!password_verify($_POST['password'], $credentials['password'])) {
    return header('Location: index.php?errorLogin=Invalid password');
}

$_SESSION['userId'] = $credentials['id'];

return header('Location: chat.php');
