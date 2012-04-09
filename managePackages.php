<?php
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - Manage Packages';
	include ('./includes/header.html');
	
	echo '<h1>Manage Packages</h1>';



	//footer:
	include ('./includes/footer.html');
	

?>

