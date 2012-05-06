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
	$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
	if (mysqli_connect_errno()) 
	{
		echo "<p class='error'>Connection failed, contact system administrator</p>";
		exit();
	}
	
	$displaySearchRes = 0;
	
	//If user searches
	if (isset($_POST['submitSearch']))	
	{
		// Check for item name
		if (!empty($_POST['itemName'])) 
		{
			$itemName = mysqli_real_escape_string($mysqli, trim($_POST['itemName']));
			$displaySearchRes = 1;	
		} 			
	}
	
	echo '
	<form action="viewAvailable.php" method="POST" name="itemSearch">
		<fieldset class="forms">
			<legend><h2>Find an Item</h2></legend>									
			<table>
				<tr>
					<td>  
						<label for="name"><b>Item Name</b></label>								
					</td>
					<td>
						<input type="text" name="itemName" id="itemName" value="" />			
					</td>
				</tr>
				<tr>
					<td><input type="submit" name="submitSearch" value="Search" /></td>
					<td><input type="submit" name="resetSearch" value="Reset" />
					<input type="hidden" name="submitted" value="1" /></td>
			</table>
		</fieldset>
	</form><br /><br />';
		
	// Table header:
	echo '	
	<div class="outer">
	<div class="innera">';
	echo'
	<table id="itemsTable" class="tablesorter" cellspacing="0" celpadding="3"> 
		<thead> 
			<tr align="left">
				<th width="310"><b>Item Name</b></th>
				<th width="170"><b>Category</b></th>
				<th width="140"><b>Quantity Available</b></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5"></td>
			</tr>
		</tfoot>
		<tbody>';
			
	// if search has not been used, display all available items
	if($displaySearchRes == 0)
	{	
		if ($mysqli->multi_query("SELECT * FROM items_available ORDER BY 'CATEGORY NAME', 'ITEM NAME';")) 
		{	
			do 
			{
				if ($result = $mysqli->store_result()) 
				{
					while ($row = $result->fetch_row()) 
					{
						//printf("%s\n", $row[1]);
						echo '<tr>
							<td width="310"><b>' . $row[1] . '</b></td>
							<td width="170">' . $row[5] . '</td>
							<td width="125">' . $row[3] . '</td>
						</tr>';
					}
				  $result->close();
			   }
		  } while ($mysqli->next_result());
		}
		else 
		{
		  echo "<p class='error'>Invalid Query</p>";
		}				
	} else 
	{	
		if ($mysqli->multi_query('CALL regex_search("ITEM", "NAME", "' . $itemName . '");')) 
		{	
			do 
			{
				if ($result = $mysqli->store_result()) 
				{
					while ($row = $result->fetch_row()) 
					{
						//printf("%s\n", $row[1]);
						echo '<tr>
							<td width="310"><b>' . $row[1] . '</b></td>
							<td width="170">' . $row[5] . '</td>
							<td width="125">' . $row[3] . '</td>
						</tr>';
					}
				  $result->close();
			   }
		  } while ($mysqli->next_result());
		}
		else {
		  echo "<p class='error'>Invalid Query</p>";
		}		
	}
	
	echo '</tbody></table></div></div><br /><br />';
	
	
	//free result and close db
	mysqli_close($mysqli); 
	
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