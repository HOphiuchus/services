<?php
include 'connection.php';
include 'pushnotification.php';
include 'sendmail.php';

$db = new mysqli($host, $username, $password, $database);

$input = file_get_contents('php://input');
$request = json_decode($input, true);

$ownerID = $request['owner_id'];
$title = $request['title'];
$description = $request['description'];
$location = $request['location'];
$appointmentDateList = $request['appointmentdatelist'];
$attendeeIDList = $request['attendeeidlist'];
$attendeeEmailList = $request['attendeeemaillist'];


$sql = "select name from person where id = ".$ownerID;

$result = $db->query($sql);

if($row = $result->fetch_row())
{
	$ownerName = $row[0];
}
		
		

$sql = "insert into appointment (title, description, location, owner_id) values ('".$title."', '".$description."', '".$location."', ".$ownerID.")";

$stmt = $db->prepare($sql);
$stmt->execute();

$appointmentID = $db->insert_id;
$stmt->close();

$appointmentDateIDList = array();
//$sql = "insert into appointmentdate (date, appointment_id) values('".$appointmentDate."', '".$appointmentID."');
$sql = 'insert into appointmentdate (date, appointment_id, determined) values(?, ?, false)';
$stmt = $db->prepare($sql);
foreach ($appointmentDateList as $appointmentDate)
{
	$stmt->bind_param('sd', $appointmentDate, $appointmentID);
	$stmt->execute();
	$appointmentDateIDList[] = $db->insert_id;
}
$stmt->close();

$sqlJujuUser = 'select devicetoken, email, name, isjujuuser from person where id = ?';
$sqlNoJujuUser = "insert into person (name, email, isjujuuser) values (?,?,?)";
$sqlInvitedAppointment = 'insert into person_invitedappointment (person_id, appointment_id) values (?, ?)';

$stmtJujuUser = $db->prepare($sqlJujuUser);
$stmtInvitedAppointment = $db->prepare($sqlInvitedAppointment);
$stmtNoJujuUser = $db->prepare($sqlNoJujuUser);

foreach ($attendeeEmailList as $email)
{
	$isJujuUser = false;
	$stmtNoJujuUser->bind_param('ssd', $email, $email, $isJujuUser);

	$stmtNoJujuUser->execute();
	$attendeeID = $db->insert_id;
	
	$stmtInvitedAppointment->bind_param('dd', $attendeeID, $appointmentID);
	$stmtInvitedAppointment->execute();
	
	//send email
	$appointmentAddress = '<a href="http://www.juju51.com/mevent/mvote/'.$appointmentID.'?user='.$attendeeID.'">
				http://www.juju51.com/mevent/mvote/'.$appointmentID.'?user='.$attendeeID.'</a>';
	send_email($email, "You are invited by ".$ownerName."!", "You are invited to join appointment by ".$ownerName.
	"! Please help ".$ownerName." to schedule the appointment by voting the appointment date via the following link:<br/>"
			.$appointmentAddress."<br/><br/>Cheers,<br/>Yours juju51 Team");
}

foreach ($attendeeIDList as $attendeeID)
{
	$stmtJujuUser->bind_param('d', $attendeeID);
	$stmtJujuUser->execute();
	$stmtJujuUser->store_result();
	$stmtJujuUser->bind_result($deviceToken, $email, $name, $isJujuUser);
	$stmtJujuUser->fetch();
	
	$stmtInvitedAppointment->bind_param('dd', $attendeeID, $appointmentID);
	$stmtInvitedAppointment->execute();

	if($isJujuUser)
	{
		if($attendeeID != $ownerID)
		{
			push_notification($deviceToken, 'Invite', 'You are invited!');
		}
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

echo json_encode(array('appointment_id' => $appointmentID, 'appointmentdateidlist' => $appointmentDateIDList));


$stmtJujuUser->close();
$stmtNoJujuUser->close();
$stmtInvitedAppointment->close();
$db->close();

?>