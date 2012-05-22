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
		$page_title = 'MHIS - Damaged Items';
		include ('./includes/header.html');
		echo "<h1>Damaged Items</h1>";	

		//Connect & Query DB:
		$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
		$query = "SELECT * FROM ITEMS_DAMAGED";
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
						
						<th width="610"><b>Item Name</b></th>
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
						
						<td width="610">' . $row['NAME'] . '</td>
					</tr>';
				}
			echo '</tbody></table></div></div><br /><br />';
							
		} else
		{
			echo "<h2>There are currently no damaged items</h2>";	
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