<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$id = $_POST['id'];

$sqlAppointment = "select a.id, title, description, location, determineddate_id from appointment a where a.owner_id = ".$id;

$stmtAppointment = $db->prepare($sqlAppointment);

$stmtAppointment->execute();
$stmtAppointment->store_result();
$stmtAppointment->bind_result($id, $title, $description, $location, $determinedDateID);

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
				'determineddate'=>$determinedDate);
	}
	else
	{
		$rows[] = array('id'=>$id, 'title'=>$title, 'description'=>$description, 'location'=>$location);
	}
}

echo json_encode(array('ownedappointmentlist' => $rows));

$stmtAppointment->close();
$stmtDeterminedDate->close();
$db->close();

?>