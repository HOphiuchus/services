<?php

include 'connection.php';
include 'pushnotification.php';
include 'sendmail.php';

$db = new mysqli($host, $username, $password, $database);

$appointmentID = $_POST['appointment_id'];
$dateID = $_POST['date_id'];

$sql = "update appointment set determineddate_id = ".$dateID." where id = ".$appointmentID;
$stmt = $db->prepare($sql);
$stmt->execute();
$stmt->close();

$sql = "update appointmentdate set determined = true where id = ".$dateID;
$stmt = $db->prepare($sql);
$stmt->execute();
$stmt->close();

$sql = "select devicetoken, p.username as username, p.email as email, p.isjujuuser as isjujuuser from person p, person_invitedappointment pi, appointment a where pi.appointment_id = a.id and a.owner_id <> p.id and pi.person_id = p.id and a.id = ".$appointmentID;
$result = $db->query($sql);
$message = array('appointment_id'=>$appointmentID, 'date_id'=>$dateID);
while($row = $result->fetch_assoc())
{
	$deviceToken = $row['devicetoken'];
	$username= $row['username'];
	$isJujuUser = $row['isjujuuser'];
	$email = $row['email'];
	if($isJujuUser)
	{
		push_notification($deviceToken, 'Determine', 'Appointment date determined!', $message);
	}
	else
	{
		//send email
		send_email($email, 'Appointment date determined!', 'Appointment date determined!');
	}
}

$db->close();
?>