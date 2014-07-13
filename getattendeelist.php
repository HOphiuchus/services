<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$appointmentID = $_POST['appointment_id'];

$sql = "select id, username, name, isjujuuser from person where id in (select person_id from person_invitedappointment where appointment_id = ".$appointmentID.")";

$result = $db->query($sql);

$rows = array();

while($row = $result->fetch_assoc())
{
	$rows[] = $row;

}
echo json_encode(array('attendeelist' => $rows));

$result->close();
$db->close();

?>