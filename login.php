<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$username = $_POST['username'];
$password = $_POST['password'];
$deviceToken = $_POST['devicetoken'];

$sqlGetUser = "select id, name, username, phone, devicetoken from person where username = '".$username."' and password = '".$password."'";

$resultGetUser = $db->query($sqlGetUser);

if($row = $resultGetUser->fetch_row())
{
	if($row[4] != $deviceToken)
	{
		$sqlUpdateToken = "update person set devicetoken = '".$deviceToken."' where id = '".$row[0]."'";
		$stmt = $db->prepare($sqlUpdateToken);
		$stmt->execute();
		$stmt->close();
	}
	echo json_encode(array('id' => $row[0], 'name' => $row[1], 'username' => $row[2], 'phone' => $row[3]));

}
$resultGetUser->close();
$db->close();

?>