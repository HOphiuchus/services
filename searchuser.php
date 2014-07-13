<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$name = $_POST['name'];

$sql = "select id, name, username, isjujuuser from person where name like '%".$name."%'";

$result = $db->query($sql);

$rows = array();
while($row = $result->fetch_assoc())
{
	$rows[] = $row;
}
echo json_encode(array('userlist' => $rows));
$result->close();
$db->close();

?>