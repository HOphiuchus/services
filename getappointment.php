<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$id = $_POST['appointment_id'];

$sql = "select id, title, description, location, determineddate from appointment where id = '".$id;

$result = $db->query($sql);

$row = $result->fetch_assoc();

echo json_encode(array('appointment' => $row));

$result->close();
$db->close();

?>