<?php

include 'connection.php';

$db = new mysqli($host, $username, $password, $database);

$input = file_get_contents('php://input');
$request = json_decode($input, true);

$addressBook = $request['addressbook'];

$rows = array();

for($i = 0; $i < sizeof($addressBook); $i++)
{
	$people = $addressBook[$i];

	$firstname = $people['firstname'];
	$lastname = $people['lastname'];
	$phoneList = $people['phonelist'];
	if(sizeof($phoneList) > 0)
	{
		$sql = "select id, username, name, isjujuuser, ".$i." as indx from person where phone in (";
		for($j = 0; $j < sizeof($phoneList); $j++)
		{

			$sql = $sql."'".$phoneList[$j]."'";
			if($j < sizeof($phoneList) - 1)
			{
				$sql = $sql.", ";
			}
		}
		$sql = $sql.")";
	
		$result = $db->query($sql);
		$row = $result->fetch_assoc();
		if($row)
		{
			$rows[] = $row;
		}
	}
}


echo json_encode(array('userlist' => $rows));

$result->close();
$db->close();

?>