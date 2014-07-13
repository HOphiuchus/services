<?php
function send_email($to, $subject, $body) 
{
	$sender = 'juju51.com'; // Enter the sender name
	$username = 'support@juju51.com'; // Enter your Email
	$password = 'juju5151!'; // Enter the Password
	
	require './PHPMailer/PHPMailerAutoload.php';
	
	// Create a new PHPMailer instance
	$mail = new PHPMailer ();
	
	// Set up SMTP
	$mail->IsSMTP ();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	$mail->Host = "mail.juju51.com";
	// $mail->Port = 587; // we changed this from 486
	$mail->Username = $username;
	$mail->Password = $password;
	
	// Build the message
	$mail->Subject = $subject;
	$mail->msgHTML ( file_get_contents ( 'contents.html' ), dirname ( __FILE__ ) );
// 	$mail->AltBody = 'This is a plain-text message body';
	$mail->Body = $body;
	$mail->isHTML(true);
// 	$mail->addAttachment ( 'images/phpmailer_mini.gif' );
	
	// Set the from/to
	$mail->setFrom ( $username, $sender);
// 	foreach ( $recipients as $address => $name ) {
// 		$mail->addAddress ( $address, $name );
// 	}
	$mail->addAddress ($to);
	$mail->send ();
	// send the message, check for errors
// 	if (! $mail->send ()) {
// 		echo "Mailer Error: " . $mail->ErrorInfo;
// 	} else {
// 		echo "Message sent!";
// 	}
}
?>
