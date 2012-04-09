<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - View Available';
	include ('./includes/header.html');
	
	echo '<h1>View Available Items</h1>';

	//Connect & Query DB:
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
	$query = "SELECT ITEM.NAME, ITEM.AVAILABLE, CATEGORY.NAME AS CAT_NAME FROM ITEM INNER JOIN CATEGORY ON ITEM.CATEGORY_ID = CATEGORY.CATEGORY_ID WHERE AVAILABLE > 0 ORDER BY CAT_NAME, NAME;";
	//$query = 'SELECT * FROM items_available ORDER BY Category, Name';						

	$result = @mysqli_query ($dbc, $query); 
	
	$numRows = mysqli_num_rows($result);
	
	if ($numRows > 0) { 
	
		echo "<h2>There are currently $numRows available items</h2>";
	
		// Table header:
		echo '<table><tr><td width="310"><h3>Item Name</h3></td>
			<td width="140"><h3>Item Quantity</h3></td>
			<td><h3>Item Category</h3></td></tr></table>
			<div class="scrollBox"><table width="100%" cellspacing="0" cellpadding="2">';
		
		$bg = '#ebe3c3';
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
			$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
			echo '<tr bgcolor="' . $bg . '"><td width="310">' . $row['NAME'] . '</td><td width="140">' . 
			$row['AVAILABLE'] . '</td><td>' . $row['CAT_NAME'] . '</td></tr>';
		}
	
		echo '</table></div>'; // Close the table & div
		
		mysqli_free_result ($result);
	
	} else { // If no items were returned
		echo '<p class="error">There are currently no items.</p>';
	}
	
	//Close the db connection
	mysqli_close($dbc); 
	
	//footer:
	include ('./includes/footer.html');
?>