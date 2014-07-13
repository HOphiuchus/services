<?php
function push_notification($deviceToken, $type, $text, $message, $badge)
{
	// Put your device token here (without spaces):
	//$deviceToken = '804c6e1afb9191ad4d8cb7be060eebbe22cd022d527c2a5c9e39c2a0280f5110';
	
	// Put your private key's passphrase here:
	$passphrase = 'juju5151';
	
	// Put your alert message here:
	//$message = 'My first push notification!';
	////////////////////////////////////////////////////////////////////////////////
	
	$ctx = stream_context_create();
// 	stream_context_set_option($ctx, 'ssl', 'local_cert', 'juju51_push_sandbox.pem');
	stream_context_set_option($ctx, 'ssl', 'local_cert', 'juju51_push.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
	
	// Open a connection to the APNS server
// 	$fp = stream_socket_client(
// 		'ssl://gateway.sandbox.push.apple.com:2195', $err,
// 		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

	$fp = stream_socket_client(
			'ssl://gateway.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	
	if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);
	
// 	echo 'Connected to APNS' . PHP_EOL;
	
	// Create the payload body
	$aps = array(
		'alert' => $text,
		'sound' => 'default',
		'content-available' => 1 
		);

	if($message)
	{
		$body = array('aps'=>$aps, 'type'=>$type, 'message'=>$message);
	}
	else 
	{
		$body = array('aps'=>$aps, 'type'=>$type);
	}
	//
	// Encode the payload as JSON
	$payload = json_encode($body);
	
	// Build the binary notification
	$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
	
	// Send it to the server
	$result = fwrite($fp, $msg, strlen($msg));
	
// 	if (!$result)
// 		echo 'Message not delivered' . PHP_EOL;
// 	else
// 		echo 'Message successfully delivered' . PHP_EOL;
	
	// Close the connection to the server
	fclose($fp);
}

?>

