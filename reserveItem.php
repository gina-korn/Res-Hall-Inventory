<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - Reserve Item';
	include ('./includes/header.html');	
?>
<h1>Reserve an Item</h1>
<br /><br /><br /><br /><br />



<?
//footer:
include ('./includes/footer.html');
?>