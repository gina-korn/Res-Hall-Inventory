<?php

	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}


	$admin = $_POST['admin'];
	$studID = $_POST['studID'];
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	$email = $_POST['email'];
	$hall = $_POST['hall'];
	$room = $_POST['room'];
	$password = $log->createPassword();
	
	
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
	
	$query = 'INSERT INTO USER
				(`STUDENT_ID` ,
				 `F_NAME` ,
				 `L_NAME` ,
				 `EMAIL` ,
				 `encryptedPassword` ,
				 `admin` ,
				 `ROOM_NUMBER` ,
				 `HALL_ID` ,
				 `AVAILABLE_CO` ,
				 `transaction_count`)
				VALUES 
				(
				"' . $studID . '",  "' . $fName . '",  "' . $lName . '",  "' . $email . '",  MD5("' . $password . '"),  ' . $admin . ',  ' . $room . ',  ' . $hall . ',  ' . 1 . ',  ' . 0 . '
				);';

	$result = @mysqli_query($dbc, $query);
	
	$to      = $email;
$subject = 'New Account - Residence Hall';
$message = "<html><body>$fName, you now have an account to check in/out items for the Residence Hall.<p>To log in, use your email address along with this password: <strong>$password</strong>.</p></body></html>";
$headers = 'From: ResidenceHall@jamespettit.net.com' . "\r\n" .
$headers .= 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    'Reply-To: webhelp@ewu.edu' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

header("location: http://www.jamespettit.net/manageUsers.php?m=true");
	


?>