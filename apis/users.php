<?php

require_once "../Classes/UserApi.php";
require_once "../Classes/Connection.php";

$connection = new Connection();
$userApi = new UserApi($connection->getConnection());

//echo json_encode($userApi->getUsers());
switch($_POST["mode"])
{case "getUsers":
    $users = $userApi->getUsers();
    echo json_encode($users);
    break;}