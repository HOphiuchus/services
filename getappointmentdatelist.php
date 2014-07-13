<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$appointmentID = $_POST['appointment_id'];

$sqlAppointmentDate = "select id, date, appointment_id, determined from appointmentdate where appointment_id = ".$appointmentID;

$resultAppointmentDate = $db->query($sqlAppointmentDate);

$appointmentDateList = array();

while($appointmentDate = $resultAppointmentDate->fetch_assoc())
{
	$sqlAttendee = "select p.id as id, name, username, isjujuuser from vote v, person p where v.attendee_id = p.id and appointmentdate_id = ".$appointmentDate['id'];
	$resultAttendee = $db->query($sqlAttendee);
	$attendeeList = array();
	while($attendee = $resultAttendee->fetch_assoc())
	{
		$attendeeList[] = $attendee;
	}
	$appointmentDate['attendeelist'] = $attendeeList;
	$appointmentDateList[] = $appointmentDate;
	$resultAttendee->close();
}
echo json_encode(array('appointmentdatelist' => $appointmentDateList));

$resultAppointmentDate->close();
$db->close();

?>