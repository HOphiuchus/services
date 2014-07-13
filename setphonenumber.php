<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$phone = $_POST['phone'];
$id = $_POST['person_id'];

$sql = "update person set phone = '".$phone."' where id = '".$id."'";
$stmt = $db->prepare($sql);
$stmt->execute();

$stmt->close();
$db->close();

?>