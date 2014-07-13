<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$id = $_POST['id'];

$sql = "select id, name, username, isjujuuser from person where id in (select contact_id from person_contact where person_id = ".$id.")";

$result = $db->query($sql);

$rows = array();

while($row = $result->fetch_assoc())
{
	$rows[] = $row;

}
echo json_encode(array('contactlist' => $rows));

$result->close();
$db->close();

?>