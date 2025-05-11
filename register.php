<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return header('Location: index.php?errorRegister=Invalid request');
}

include_once 'Classes/Connection.php';
include_once 'Classes/User.php';

session_start();

$_SESSION['old'] = $_POST;

if ($_POST['email'] == '' || $_POST['name'] == '' || $_POST['surname'] == '' || $_POST['password'] == '') {
    return header('Location: index.php?errorRegister=Please fill all the fields');
}

$credentials = $_POST;
$credentials['password'] = password_hash($credentials['password'], PASSWORD_DEFAULT);

$user = new User();
$existingUser = $user->getUserByEmail($credentials['email']);

if ($existingUser) {
    return header('Location: index.php?errorRegister=Email already exists');
}

$user->setUser($credentials);

$newUser = $user->getUserId($credentials['email']);

$_SESSION['userId'] = $newUser['id'];

return header('Location: chat.php');
