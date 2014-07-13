<?php
include 'connection.php';
include 'pushnotification.php';
include 'sendmail.php';

$db = new mysqli($host, $username, $password, $database);

$input = file_get_contents('php://input');
$request = json_decode($input, true);

$appointmentID = $request['appointment_id'];
$attendeeIDList = $request['attendeeidlist'];

$sqlPerson = 'select devicetoken, email, name, isjujuuser from person where id = ?';
$sqlInvitedAppointment = 'insert into person_invitedappointment (person_id, appointment_id) values (?, ?)';

$stmtPerson = $db->prepare($sqlPerson);
$stmtInvitedAppointment = $db->prepare($sqlInvitedAppointment);

foreach ($attendeeIDList as $attendeeID)
{
	$stmtPerson->bind_param('d', $attendeeID);
	$stmtPerson->execute();
	$stmtPerson->store_result();
	$stmtPerson->bind_result($deviceToken, $email, $name, $isJujuUser);
	$stmtPerson->fetch();
	
	$stmtInvitedAppointment->bind_param('dd', $attendeeID, $appointmentID);
	$stmtInvitedAppointment->execute();

	if($isJujuUser)
	{
		push_notification($deviceToken, 'Invite', 'You are invited!');
	}
	else 
	{
		//send email
		$appointmentAddress = '<a href="http://www.juju51.com/mevent/mvote/'.$appointmentID.'?user='.$attendeeID.'">
				http://www.juju51.com/mevent/mvote/'.$appointmentID.'?user='.$attendeeID.'</a>';
		send_email($email, "You are invited by ".$ownerName."!", "You are invited to join appointment by ".$ownerName.
			"! Please help ".$ownerName." to schedule the appointment by voting the appointment date via the following link:<br/>"
			.$appointmentAddress."<br/><br/>Cheers,<br/>Yours juju51 Team");
	}

}

$stmtPerson->close();
$stmtInvitedAppointment->close();
$db->close();

?>