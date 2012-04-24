<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - View All Items';
	include ('./includes/header.html');
	echo "<h1>View All Items</h1>";	

	//Connect & Query DB:
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
	$query = "SELECT * FROM ITEM ORDER BY NAME";	
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
					<th width="140"><b>Quantity</b></th>
					<th width="80"><b>Available</b></th>
					<th width="90"><b>Category ID</b></th>
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
					<td width="298"><b>' . $row['NAME'] . '</b></td>
					<td width="150">' . $row['QUANTITY'] . '</td>
					<td width="80">' . $row['AVAILABLE'] . '</td>
					<td width="80">' . $row['CATEGORY_ID'] . '</td>
				</tr>';
			}
		echo '</tbody></table></div></div><br /><br />';
						
	} else
	{
		echo "<h2>View All Report currently empty</h2>";	
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