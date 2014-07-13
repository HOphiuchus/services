<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$sqlGetUser = "select id, name, username, phone, devicetoken from person";

$resultGetUser = $db->query($sqlGetUser);

$row = $resultGetUser->fetch_row();

echo $row;

?>