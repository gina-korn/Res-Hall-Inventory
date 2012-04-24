<?php
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - Manage Items';
	include ('./includes/header.html');
	
	echo '<h1>Manage Items</h1>';

	// $pageType is used by the page to determine whether to display the add form or the edit form
	$pageType = 'add';
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: '.mysqli_connect_error());
	
	if (isset($_POST['submitted'])) 
	{
		$submitVal = $_POST['submitted'];
		$hallID = 1; // Hall ID for the items can be changed here for now, could add drop down for hall names in the future
		$errorArray = array(); // error array
	
		// Check for errors that both forms have in common
		if($submitVal == 1 || $submitVal == 3)
		{
			// Check for item name
			if (empty($_POST['itemName'])) 
			{
				$errorArray[] = 'You forgot to enter the item name.';
			} else {
				$itemName = mysqli_real_escape_string($dbc, trim($_POST['itemName']));
			}
			
			// Check for quantity
			if (empty($_POST['itemQuantity'])) 
			{
				$errorArray[] = 'You forgot to enter a quantity.';
				
			} else {
				$itemQuantity = mysqli_real_escape_string($dbc, trim($_POST['itemQuantity']));
				if (is_numeric($itemQuantity) && $itemQuantity > 0) 
				{} else {
					$errorArray[] = 'Invalid quantity. Please enter a number greater than 0';
					$itemQuantity = NULL;
				}
			}// end of quantity check
			
			// Check for available quantity (other fields were checked above
			if (empty($_POST['itemAvailable'])) 
			{
				$itemAvailable = 0;
				
			} else {
				$itemAvailable = $_POST['itemAvailable'];
				if (is_numeric($itemAvailable) && $itemAvailable >= 0) 
				{} else {
					$errorArray[] = 'Invalid available quantity. Number must be 0 or greater.';
					$itemAvailable = NULL;
				}
			}
			
			if($itemAvailable > $itemQuantity)
			{
				$errorArray[] = 'Available quantity cannot exceed actual quantity.';
			}
			
			$itemID = trim($_POST['itemID']);
			
			$itemCategory = trim($_POST['itemCategory']);
			$itemPackage = trim($_POST['itemPackage']);
		}
		
		if($submitVal == 1) // Add item
		{					
			// Making sure there isn't already an item with the same name
			if (empty($errorArray)) 
			{ 
				$q = "SELECT * FROM ITEM WHERE NAME = '$itemName';";	
				$r = @mysqli_query ($dbc, $q);
				$numRows = mysqli_num_rows($r);
				if ($numRows > 0) 
				{ 
					$errorArray[] = 'Item name already in use, please re-enter';
				}
			}// end if errors
			
			
			// No errors, add new item
			if (empty($errorArray)) 
			{ 
				$query = "INSERT INTO ITEM (NAME, QUANTITY, AVAILABLE, HALL_ID, CATEGORY_ID, PACKAGE_ID) 
					VALUES ('$itemName', '$itemQuantity', '$itemAvailable', '$hallID', '$itemCategory', '$itemPackage' );";		
				$result = @mysqli_query ($dbc, $query);
				
				if ($result) 
				{
					echo "<p><b>$itemName has successfully been added.</b></p>";	
					
				} else 
				{
					echo '<p class="error"><b>System Error</b><br />
						Item could not be added due to a system error. We apologize for any inconvenience.</p>'; 								
				} // end of if ($result)				
			}// end of if empty	
		}//end of $submitVal == 1
		
		
		//item is to be deleted
		if(($_POST['itemDelete'] != NULL) && ($submitVal == 2))
		{
			
			$item = $_POST['itemDelete']; 
			
			// Check for checked-out items
			$query = "SELECT CHECKED_OUT_COUNT FROM ITEM WHERE ITEM.ITEM_ID = $item;";
			$result = @mysqli_query ($dbc, $query);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if($row['CHECKED_OUT_COUNT'] > 0)
			{
				$errorArray[] = 'Cannot delete an item that is currently checked out';
			}
			
			if (empty($errorArray)) 
			{
				$query = "DELETE FROM ITEM WHERE ITEM.ITEM_ID = $item;";		
				$result = @mysqli_query ($dbc, $query);
				
				if ($result) 
				{
					echo "<p><b>Item has successfully been removed.</b></p>";	
					
				} else 
				{
					echo '<p class="error"><b>System Error</b><br />
						Item could not be removed due to a system error. We apologize for any inconvenience.</p>'; 							
				} // End of if ($result)
			}
		}// End of delete
		
		if($submitVal == 3 && !isset($_POST['cancelEdit'])) //edit item (post)
		{			
			// Checking for duplicate item names 
			if (empty($errorArray)) 
			{ 
				$q = "SELECT * FROM ITEM WHERE NAME = '$itemName';";	
				$r = @mysqli_query ($dbc, $q);
				$numRows = mysqli_num_rows($r);
				if ($numRows > 0) 
				{ 
					$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
					$newID = $row['ITEM_ID'];
					if($newID != $itemID)
					{
						$errorArray[] = 'Item name already in use, please re-enter';	
					}
				}
			}// end if errors
			
			// No errors, edit item
			if (empty($errorArray)) 
			{ 
				$query = "UPDATE ITEM SET NAME='$itemName', QUANTITY='$itemQuantity', AVAILABLE='$itemAvailable', 
					HALL_ID='$hallID', CATEGORY_ID='$itemCategory', PACKAGE_ID='$itemPackage' 
					WHERE ITEM_ID='$itemID' LIMIT 1;";		
				$result = @mysqli_query ($dbc, $query);
				
				if ($result) 
				{
					echo "<p><b>$itemName has successfully been edited.</b></p>";
					unset($_POST); // clearing the post vars, or they show up in the add boxes when the page loads						
					
				} else 
				{
					echo '<p class="error"><b>System Error</b><br />
						Item could not be added due to a system error. We apologize for any inconvenience.</p>'; 								
				} // end of if ($result)
								
			} else {
		
				$pageType = 'edit';	
			}
			
		}//end of $submitVal == 3
		
		// display any errors
		if (!empty($errorArray)) 
		{ 	
			echo '<p class="error">Error! The following error(s) occurred:</p><ul>';
			
			foreach ($errorArray as $error) 
			{
				echo "<li class='error'>$error</li>";
			}
			echo '</ul><p class="error">Please try again.</p>';			
		}
		
	} // End of if (isset($_POST['submitted']))
	
	if (isset($_POST['cancelEdit']))
	{
		$pageType == 'add';
	}

/////// Forms ////////////
	// edit item form
	if(($_POST['itemEdit'] != NULL) || $pageType == 'edit')
	{
		if (isset($_POST['itemID']))
		{
			$item = $_POST['itemID'];
		}
		else
		{
			$item = $_POST['itemEdit'];
		}
		
		$query = "SELECT * FROM ITEM WHERE ITEM.ITEM_ID = $item;";		
		$r = @mysqli_query ($dbc, $query);
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		
		echo '
		<form action="manageItems.php" method="POST" name="editItemsForm">
			<fieldset class="forms">
				<legend><h2>Edit</h2></legend>									
				<table>
					<tr>
						<td>  
							<label for="name"><b>Item Name</b></label>								
						</td>
						<td>
							<input type="text" name="itemName" id="itemName" value="' . $row['NAME'] . '" /> 
							<input type="hidden" name="itemID" value="' . $row['ITEM_ID'] . '" /> 			
						</td>
						<td>  
							<label for="package"><b>Item Package</b></label>								
						</td>
						<td>
							<select id="itemPackage" name="itemPackage">
							<optgroup label="Item Package">';
					   
								$query = "SELECT * FROM PACKAGE ORDER BY package_name;";		
								$r = @mysqli_query ($dbc, $query); 
								
								while ($row3 = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
								{
									if($row3['package_id'] == $row['PACKAGE_ID'])
									{
										echo '<option selected="selected" value="' . $row3['package_id'] . '">' . $row3['package_name'] . '</option>';
									} else 
									{
										echo '<option value="' . $row3['package_id'] . '">' . $row3['package_name'] . '</option>';
									}
								
								}//end while
							echo '        
							</optgroup>
							</select>																
						</td>
					</tr>
					<tr>
						<td>
							<label for="quantity"><b>Quantity</b></label>	
						</td>
						<td>
							<input type="text" name="itemQuantity" id="itemQuantity" value="' . $row['QUANTITY'] . '" />	
						</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td>
							<label for="quantityAvail"><b>Quantity Available</b></label>	
						</td>
						<td>
							<input type="text" name="itemAvailable" id="itemAvailable" value="' . $row['AVAILABLE'] . '" />
						</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td>
							<label for="category"><b>Category</b></label>								
						</td>
						<td>
							<select id="itemCategory" name="itemCategory">
							<optgroup label="Item Category">';
					   
								$query = "SELECT CATEGORY_ID, NAME FROM CATEGORY ORDER BY NAME;";		
								$r = @mysqli_query ($dbc, $query); 
								
								while ($row2 = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
								{
									//set the category list to item's category
									if($row2['CATEGORY_ID'] == $row['CATEGORY_ID'])
									{
										echo '<option selected="selected" value="' . $row2['CATEGORY_ID'] . '">' . $row2['NAME'] . '</option>';
									} else 
									{
										echo '<option value="' . $row2['CATEGORY_ID'] . '">' . $row2['NAME'] . '</option>';
									}
								
								}//end while
							echo '        
							</optgroup>
							</select>																
						</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="editItem" value="Submit Changes" />
							<input type="hidden" name="submitted" value="3" />						
						</td>	
						<td colspan="3"></td>
					</tr>
				</table>
			</fieldset>
		</form>	
		<br /><br />';
		
		exit();	
	}// end of edit form		

	if($pageType == 'add')
	{
		echo '
		<form action="manageItems.php" method="POST" name="addItemForm">
			<fieldset class="forms">
				<legend><h2>Add an Item</h2></legend>									
				<table>
					<tr>
						<td>  
							<label for="name"><b>Item Name</b></label>								
						</td>
						<td>
							<input type="text" name="itemName" id="itemName" value="';
							if(isset($_POST['itemName'])) echo $_POST['itemName'];
							echo '" /> 			
						</td>
						<td>  
							<label for="package"><b>Item Package</b></label>								
						</td>
						<td>
							<select id="itemPackage" name="itemPackage">
							<optgroup label="Item Package">';
					   
								$query = "SELECT package_id, package_name FROM PACKAGE ORDER BY package_name;";		
								$r = @mysqli_query ($dbc, $query); 
								
								while ($row3 = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
								{
									echo '<option value="' . $row3['package_id'] . '">' . $row3['package_name'] . '</option>';
									
								}//end while
							echo '        
							</optgroup>
							</select>																
						</td>
					</tr>
					<tr>
						<td>
							<label for="quantity"><b>Quantity</b></label>	
						</td>
						<td>
							<input type="text" name="itemQuantity" id="itemQuantity" value="';
							if(isset($_POST['itemQuantity'])) echo $_POST['itemQuantity'];
							echo '" />	
						</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td>
							<label for="quantityAvail"><b>Quantity Available</b></label>	
						</td>
						<td>
							<input type="text" name="itemAvailable" id="itemAvailable" value="';
							if(isset($_POST['itemAvailable'])) echo $_POST['itemAvailable'];
							echo '" />
						</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td>
							<label for="category"><b>Category</b></label>								
						</td>
						<td>
							<select id="itemCategory" name="itemCategory">
							<optgroup label="Item Category">';
					   
								$q = "SELECT CATEGORY_ID, NAME FROM CATEGORY ORDER BY NAME;";		
								$r = @mysqli_query ($dbc, $q); 
								
								while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
								{
									echo '<option value="' . $row['CATEGORY_ID'] . '">' . $row['NAME'] . '</option>';
								
								}//end while
							
							echo '        
							</optgroup>
							</select>																
						</td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="addItem" value="Add Item" />
							<input type="hidden" name="submitted" value="1" />						
						</td>
						<td colspan="3"></td>
					</tr>
				</table>
			</fieldset>
		</form>';
	}//end add form
	
	echo '<br /><br />';	
	
	// Edit / Delete Form 
	$q = 'SELECT ITEM.NAME, ITEM.ITEM_ID, CATEGORY.NAME AS CAT_NAME FROM ITEM INNER JOIN CATEGORY ON 
		ITEM.CATEGORY_ID = CATEGORY.CATEGORY_ID ORDER BY CAT_NAME, NAME;';	
    $r = @mysqli_query ($dbc, $q);
	$numRows = mysqli_num_rows($r);
	if ($numRows > 0) 
	{ 
		// Table header:
		echo '<h2>Edit / Delete an Item</h2><div class="scrollBox">
			<form action="manageItems.php" method="POST" name="manageItemsForm">
			<table width="100%" cellspacing="0" cellpadding="2">';
	
		$bg = '#ebe3c3';
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
		{
			$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
			
			echo '
			<tr bgcolor="' . $bg . '">
				<td width="260"><b>' . $row['NAME'] . '</b></td>
				<td width="200">' . $row['CAT_NAME'] . '</td>
				<td valign="right">
					<button class="buttons" name="itemEdit"	value="' . $row['ITEM_ID'] . '"><img src="./images/edit.png" length="20" width="20" /></button>
					<button class="buttons" name="itemDelete" value="' . $row['ITEM_ID'] . '" onclick="return confirmation()">
						<img src="./images/delete.png" /></button>
					<input type="hidden" name="submitted" value="2" />
				</td>
			</tr>';
		}
		//confirm("This will permanently delete ' . $row['NAME'] . '. Continue?")
	
		echo '</td></tr></table></form></div>';
		
		mysqli_free_result ($r);
	
	} else { // If no items were returned
		echo '<p class="error">There are currently no items.</p>';
	}

	mysqli_close($dbc);
	//footer:
	include ('./includes/footer.html');
	
	echo '
		<script type="text/javascript" language="JavaScript">
			document.forms["addItemForm"].elements["itemName"].focus();			
			function confirmation() 
			{
				if (confirm("This will permanantly remove item. Continue?")) 
				{
					return true;
				}
				else {
					return false;
				}
			}
		</script>';
?>

