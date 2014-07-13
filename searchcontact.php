<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$username = $_POST['username'];

$sql = "select id, name, username, isjujuuser from person where username like '".$username."'";

$result = $db->query($sql);

//assume one result
if($row = $result->fetch_assoc())
{
	echo json_encode($row);
}

$result->close();
$db->close();

?>