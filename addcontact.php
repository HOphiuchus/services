<?php

include 'connection.php';
include 'pushnotification.php';
include 'sendmail.php';

$db = new mysqli($host, $username, $password, $database);

$input = file_get_contents('php://input');
$request = json_decode($input, true);

$contactList = $request['contactlist'];
$personID = $request['person_id'];
$sql = "select name from person where id = ".$personID;
$result = $db->query($sql);
$row = $result->fetch_assoc();
$personName = $row['name'];


$sqlContact = "insert into person_contact (person_id, contact_id) values (?,?)";
$stmtContact = $db->prepare($sqlContact);

$sqlPerson = "insert into person (name, email, isjujuuser) values (?,?,?)";
$stmtPerson = $db->prepare($sqlPerson);

$sqlDeviceToken = "select devicetoken from person where id = ?";
$stmtDeviceToken = $db->prepare($sqlDeviceToken);

$contactIDList = array();
foreach ($contactList as $contact)
{
	$name = $contact['name'];
	$email = $contact['email'];
	$isJujuUser = $contact['isjujuuser'];
	if($isJujuUser == 'YES')
	{
		$contactID = $contact['id'];
		
		$stmtContact->bind_param('dd', $personID, $contactID);
		$stmtContact->execute();
		
		$stmtDeviceToken->bind_param('d', $contactID);
		$stmtDeviceToken->execute();
		$stmtDeviceToken->bind_result($deviceToken);
		$stmtDeviceToken->fetch();
		
		//send notification
		$type = 'Contact';
		$text = $personName.' added you as friend.';
		push_notification($deviceToken, $type, $text);
	}
	else
	{
		$stmtPerson->bind_param('ssd', $name, $email, $isJujuUser);
		$stmtPerson->execute();
		$contactID = $db->insert_id;
		$stmtContact->bind_param('dd', $personID, $contactID);
		$stmtContact->execute();
		
		//send email
		send_email($email, $personName.' added you as friend.', $personName.' added you as friend.');
	}
	$contactIDList[] = $contactID;
}
$stmtContact->close();
$stmtPerson->close();
$stmtDeviceToken->close();

$db->close();

echo json_encode(array('contactidlist' => $contactIDList));

?>