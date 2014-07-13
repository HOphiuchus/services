<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$username = $_POST['username'];
$password = $_POST['password'];
$deviceToken = $_POST['devicetoken'];
$name = $username;

$sqlGetUser = "select username from person where username = '".$username."'";
$resultGetUser = $db->query($sqlGetUser);

if($row = $resultGetUser->fetch_row())
{
	return;
}

$sql = "insert into person (username, password, name, isjujuuser, devicetoken) values ('".$username."', '".$password."', '".$name."', true, '".$deviceToken."')";

$stmt = $db->prepare($sql);

if($stmt->execute())
{
	$personID = $db->insert_id;
	echo json_encode(array('id' => $personID, 'name' => $name, 'username' => $username));
}
$stmt->close();
$db->close();

?>