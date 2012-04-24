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
	$displaySearchRes = 0;
	
	$query = "SELECT * FROM items_available ORDER BY 'CATEGORY NAME', 'ITEM NAME';";
	
	//If user searches, overwrite query with search query	
	if (isset($_POST['submitSearch']))	
	{
		// Check for item name
		if (!empty($_POST['itemName'])) 
		{
			$itemName = mysqli_real_escape_string($dbc, trim($_POST['itemName']));
			$query = "SELECT ITEM.NAME, ITEM.AVAILABLE, CATEGORY.NAME AS CAT_NAME FROM ITEM INNER JOIN CATEGORY 
				ON ITEM.CATEGORY_ID = CATEGORY.CATEGORY_ID WHERE AVAILABLE > 0 AND ITEM.NAME 
				REGEXP '^.*$itemName.*$' ORDER BY CAT_NAME, ITEM.NAME;";
			$displaySearchRes = 1;	
		} 			
	}

	$result = mysqli_query ($dbc, $query); 
	
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
	
	if($displaySearchRes == 0)
	{
		if(!$result) 
		{
		  echo 'INVALID QUERY';
		  exit();
		}
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
			
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
			{
				echo '<tr>
				  <td width="310"><b>' . $row['NAME'] . '</b></td>
				  <td width="170">' . $row['CATEGORY NAME'] . '</td>
				  <td width="125">' . $row['AVAILABLE'] . '</td>
				</tr>';
			}
	
		echo '</tbody></table></div></div><br /><br />';
		
	} else {	
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
			
			
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
			{
				echo '<tr>
				  <td width="310"><b>' . $row['NAME'] . '</b></td>
				  <td width="170">' . $row['CAT_NAME'] . '</td>
				  <td width="125">' . $row['AVAILABLE'] . '</td>
				</tr>';
			}
		echo '</tbody></table></div></div><br /><br />';	
	}
	
	//free result and close db
	mysqli_free_result ($result);
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