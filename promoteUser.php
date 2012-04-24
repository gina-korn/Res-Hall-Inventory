<?php

	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}


	$admin = $_GET['admin'];
	$studID = $_GET['studID'];
	
	
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
	
	
	if ($admin == "True")
	{
		$query = 'UPDATE USER SET admin = 1 WHERE STUDENT_ID = ' . $studID;
		$result = @mysqli_query($dbc, $query);
	}
	else
	{
		$query = 'UPDATE USER SET admin = 0 WHERE STUDENT_ID = ' . $studID;
		$result = @mysqli_query($dbc, $query);
	}
	

header("location: http://www.jamespettit.net/manageUsers.php");
	


?>