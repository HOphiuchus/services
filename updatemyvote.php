<?php

include 'connection.php';
include 'pushnotification.php';

$db = new mysqli($host, $username, $password, $database);

$input = file_get_contents('php://input');
$request = json_decode($input, true);

$attendeeID = $request['attendee_id'];
$appointmentID = $request['appointment_id'];
$appointmentDateIDList = $request['appointmentdateidlist'];

$sql = "delete from vote where attendee_id = ".$attendeeID." and appointmentdate_id in (select id from appointmentdate where appointment_id = ".$appointmentID.")";
$stmt = $db->prepare($sql);
$stmt->execute();
$stmt->close();

$sql = "insert into vote (attendee_id, appointmentdate_id) values (?, ?)";
$stmt = $db->prepare($sql);
foreach ($appointmentDateIDList as $appointmentDateID)
{
	$stmt->bind_param('dd', $attendeeID, $appointmentDateID);
	$stmt->execute();
}
$stmt->close();

$db->close();

?>