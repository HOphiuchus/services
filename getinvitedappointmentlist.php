<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$id = $_POST['id'];

$sqlAppointment = "select a.id, title, description, location, p.id, p.username, p.name, p.isjujuuser, determineddate_id from person p, appointment a, person_invitedappointment pi where pi.appointment_id = a.id and p.id = a.owner_id and a.owner_id <> pi.person_id and pi.person_id = ".$id;

$stmtAppointment = $db->prepare($sqlAppointment);

$stmtAppointment->execute();
$stmtAppointment->store_result();
$stmtAppointment->bind_result($id, $title, $description, $location, $ownerID, $ownerUsername, $ownerName, $ownerIsJujuUser, $determinedDateID);

$rows = array();
while($stmtAppointment->fetch())
{
	if($determinedDateID)
	{
		$sqlDeterminedDate = "select date from appointmentdate where appointmentdate.id = ".$determinedDateID;
		$stmtDeterminedDate = $db->prepare($sqlDeterminedDate);
		$stmtDeterminedDate->execute();
		$stmtDeterminedDate->store_result();
		$stmtDeterminedDate->bind_result($determinedDate);
		$stmtDeterminedDate->fetch();
		$rows[] = array('id'=>$id, 'title'=>$title, 'description'=>$description, 'location'=>$location, 
				'owner_id'=>$ownerID, 'ownerusername'=>$ownerUsername, 'ownername'=>$ownerName, 'ownerisjujuuser'=>$ownerIsJujuUser, 'determineddate'=>$determinedDate);
	}
	else
	{
		$rows[] = array('id'=>$id, 'title'=>$title, 'description'=>$description, 'location'=>$location,
				 'owner_id'=>$ownerID, 'ownerusername'=>$ownerUsername, 'ownername'=>$ownerName, 'ownerisjujuuser'=>$ownerIsJujuUser);
	}
}

echo json_encode(array('invitedappointmentlist' => $rows));

$stmtAppointment->close();
$stmtDeterminedDate->close();
$db->close();

?>