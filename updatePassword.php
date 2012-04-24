<?php

	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();


	$email = $_SESSION['EMAIL'];
	$password = $_POST['new_password'];
	
	
	$log->changePassword($email, 'USER', 'encryptedPassword', $password, 'EMAIL');
	

	header("location: http://www.jamespettit.net/main.php?p=True");
	


?>