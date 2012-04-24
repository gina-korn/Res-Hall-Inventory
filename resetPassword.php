<?php

	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}


	$email = $_GET['email'];
	
	
	$log->passwordReset($email, 'USER', 'encryptedPassword', 'EMAIL');
	

	header("location: http://www.jamespettit.net/manageUsers.php?p=True");
	


?>