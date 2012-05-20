<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - Available Reports';
	include ('./includes/header.html');
	
	echo '<h1>View Available Reports</h1>
	
	<p><a href="reportPastDue.php">Past due items</a></p>
	<p><a href="reportCheckedOut.php">Currently checked out items</a></p>
	<p><a href="reportViewAll.php">View all items</a></p>
	<p><a href="reportPopularCategories.php">Popular Categories</a></p>
	<p><a href="mostCheckedOut.php">Popular Items</a></p>
	<p><a href="reportDamagedItems.php">Damaged Items</a></p>
	<p><a href="report24HourRes.php">Reservations in the next 24 hr</a></p>
	<p><a href="reportAllReservations.php">All item reservations</a></p>

	';	

	
	//footer:
	include ('./includes/footer.html');
?>
