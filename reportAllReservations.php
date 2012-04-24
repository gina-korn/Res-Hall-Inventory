<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - All Reserved Items';
	include ('./includes/header.html');
	echo "<h1>All Reserved Items</h1>";	

	//Connect & Query DB:
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
	$query = "SELECT * FROM all_items_reserved"; //order by name once it's in the db	
	$result = @mysqli_query ($dbc, $query); 	
	
	// If there is a report found
	if($result)
	{		
		echo '	
		<div class="outer">
		<div class="innera">
		<table id="itemsTable" class="tablesorter" cellspacing="0" celpadding="3"> 
			<thead> 
				<tr align="left">
					<th width="310"><b>Item Name</b></th>
					<th width="150"><b>User Name</b></th>
					<th width="150"><b>Reservation Date</b></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5"></td>
				</tr>
			</tfoot>
			<tbody>';
		  
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
			{
				echo '<tr>
					<td width="310"><b>' . $row['Item'] . '</b></td>
					<td width="150"><b>' . $row['Reserved By'] . '</b></td>
					<td width="150"><b>' . $row['Reservation Date'] . '</b></td>
				</tr>'; 
			}
		echo '</tbody></table></div></div><br /><br />';
						
	} else
	{
		echo "<h2>There are currently no reservations</h2>";	
	}
	
	//Close the db connection
	mysqli_close($dbc); 
	
	//footer:
	include ('./includes/footer.html');
?>

<script type="text/javascript" id="js">
$(document).ready(function() 
    { 
        $("#itemsTable").tablesorter({widgets: ['zebra']});
    } 
); 

</script>