<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}
	if($_SESSION['isAdmin'] == 1)
	{
		$page_title = 'MHIS - Checked-Out Items';
		include ('./includes/header.html');
		echo "<h1>Checked-Out Items</h1>";	

		//Connect & Query DB:
		$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
		$query = "SELECT * FROM checked_out_items ORDER BY name";	
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
						<th width="210"><b>Student Name</b></th>
						<th width="140"><b>Item Name</b></th>
						<th width="180"><b>Due Date</b></th>
						<th width="90"><b>Past Due</b></th>
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
						<td width="210"><b>' . $row['Checked out to'] . '</b></td>
						<td width="140">' . $row['name'] . '</td>
						<td width="180">' . $row['Due Date'] . '</td>
						<td width="80">' . $row['Past Due'] . '</td>
					</tr>';
				}
			echo '</tbody></table></div></div><br /><br />';
							
		} else
		{
			echo "<h2>No items currently checked out</h2>";	
		}
		
		//Close the db connection
		mysqli_close($dbc); 
		
		//footer:
		include ('./includes/footer.html');
	}//end admin check
?>

<script type="text/javascript" id="js">
$(document).ready(function() 
    { 
        $("#itemsTable").tablesorter({widgets: ['zebra']});
    } 
); 

</script>