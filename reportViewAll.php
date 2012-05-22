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
		$page_title = 'MHIS - View All Items';
		include ('./includes/header.html');
		echo "<h1>View All Items</h1>";	

		//Connect to DB
		$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
		if (mysqli_connect_errno()) 
		{
			echo "<p class='error'>Connection failed, contact system administrator</p>";
			exit();
		}
		
		// query and display results
		if ($mysqli->multi_query("call select_all('all_items', 1, 1);"))
		{	
			echo '	
			<div class="outer">
			<div class="innera">
			<table id="itemsTable" class="tablesorter" cellspacing="0" celpadding="3"> 
				<thead> 
					<tr align="left">
						<th width="310"><b>Item Name</b></th>
						<th width="140"><b>Category Name</b></th>
						<th width="80"><b>Available</b></th>
						<th width="90"><b>Quantity</b></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="5"></td>
					</tr>
				</tfoot>
				<tbody>';
			do 
			{
				if ($result = $mysqli->store_result()) 
				{
					while ($row = $result->fetch_row()) 
					{
						echo '<tr>
							<td width="298"><b>' . $row[0] . '</b></td>
							<td width="150">' . $row[4] . '</td>
							<td width="80">' . $row[3] . '</td>
							<td width="70">' . $row[2] . '</td>
						</tr>';
					}
				  $result->close();
			   }
			} while ($mysqli->next_result());
		  
			echo '</tbody></table></div></div><br /><br />';
		}
		else 
		{
		  echo "<h2>View All Report currently empty</h2>";
		}
		
		//Close the db connection
		mysqli_close($mysqli); 
		
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