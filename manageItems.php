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
		$page_title = 'MHIS - Manage Items';
		include ('./includes/header.html');
		
		echo '<h1>Manage Items</h1>';
		echo "<p>Note: you will get an error if you try to delete an item. This has to do with the 
			migration to stored procedures and will be fixed soon.</p>";

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
					//Connect to and query db
					$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
					if (mysqli_connect_errno()) 
					{
						echo "<p class='error'>Connect failed, please contact your system administrator</p>";
						exit();
					}
					if ($mysqli->multi_query('call select_all(\'ITEM\', \'NAME\', \'' . "$itemName" . '\');')) 
					{
						$num = 0;
						do 
						{
							if ($result = $mysqli->store_result()) 
							{
								while ($row = $result->fetch_row()) 
								{
								   $num++;
								}
							  $result->close();
							}
						}while ($mysqli->next_result());
						if($num > 0)
						{
							$errorArray[] = 'Item name already in use, please re-enter';
						}
					}
					else 
					{
						$errorArray[] = 'Error, please contact your system administrator';
					}//end query if/else
					mysqli_close($mysqli);
					
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
				
				/*
				$query = "SELECT CHECKED_OUT_COUNT FROM ITEM WHERE ITEM.ITEM_ID = $item;";
				$result = @mysqli_query ($dbc, $query);
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				if($row['CHECKED_OUT_COUNT'] > 0)
				{
					$errorArray[] = 'Cannot delete an item that is currently checked out';
				}
				*/
	//Need to check that this works once delete procedure is working			
				//Connect to and query db
				$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
				if (mysqli_connect_errno()) 
				{
					echo "<p class='error'>Connect failed, please contact your system administrator</p>";
					exit();
				}
				// Check for checked-out items
				if ($mysqli->multi_query('call select_all(\'CHECKED_OUT_COUNT\', \'ITEM\', \'' . "$item" . '\');')) 
				{
					$num = 0;
					do 
					{
						if ($result = $mysqli->store_result()) 
						{
							while ($row = $result->fetch_row()) 
							{
							   $num++;
							}
						  $result->close();
						}
					}while ($mysqli->next_result());
					if($num > 0)
					{
						$errorArray[] = 'Cannot delete an item that is currently checked out';
					}
				}
				else 
				{
					//printf("<br />Error: %s\n", $mysqli->error);
					$errorArray[] = 'Error, please contact your system administrator';
				}//end query if/else
				mysqli_close($mysqli);
				
				
				if (empty($errorArray)) 
				{
	// ERROR HERE**************************************************************
	// ************************************************************************				
					//Connect to and query db
					$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
					if (mysqli_connect_errno()) 
					{
						echo "<p class='error'>Connect failed, please contact your system administrator</p>";
						exit();
					} 
					if ($mysqli->multi_query('delete_row("ITEM", "ITEM_ID", "' . $item . '");'))//what does this return? how do I know
																								//if it's successful?
					{
						do 
						{
							//$num = 0;
							if ($result = $mysqli->store_result()) 
							{
								while ($row = $result->fetch_row()) 
								{
								   //$num++;
								}
							  $result->close();
							}
						} while ($mysqli->next_result());
						if($num > 0)
						{
							//$errorArray[] = 'Category name already in use, please re-enter';
						}				  
					}
					else 
					{
					  //Error, please contact your system administrator
					  $errorArray[] = 'System Error' . $mysqli->error;
					}				
				}
			}// End of delete
			
			if($submitVal == 3 && !isset($_POST['cancelEdit'])) //edit item (post)
			{			
				// Checking for duplicate item names 
				if (empty($errorArray)) 
				{ 			
					//Connect to and query db
					$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
					if (mysqli_connect_errno()) 
					{
						echo "<p class='error'>Connect failed, please contact your system administrator</p>";
						exit();
					}
					//I'm checking to make sure they don't rename it to an item that already exists
					//but I needed to account for the fact that if they don't rename it, it will already exist
					//and they won't be able to proceed with the edit
					if ($mysqli->multi_query('call select_all(\'ITEM\', \'NAME\', \'' . "$itemName" . '\');')) 
					{
						do 
						{
							if ($result = $mysqli->store_result()) 
							{
								while ($row = $result->fetch_row()) 
								{
									$newID = $row[0];
									if($newID != $itemID)
									{
										$errorArray[] = 'Item name already in use, please re-enter';	
									}
								}
								$result->close();
							}
						}while ($mysqli->next_result());
					}
					else 
					{
						$errorArray[] = 'Error, please contact your system administrator';
					}//end query if/else
					mysqli_close($mysqli);
					
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
			
			//Connect to and query db
			$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
			if (mysqli_connect_errno()) 
			{
				echo "<p class='error'>Connect failed, please contact your system administrator</p>";
				exit();
			}
			if ($mysqli->multi_query('call select_all(\'ITEM\', \'ITEM_ID\', \'' . "$item" . '\');')) 
			{
				do 
				{
					if ($result = $mysqli->store_result()) 
					{
						while ($row = $result->fetch_row()) 
						{
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
												<input type="text" name="itemName" id="itemName" value="' . $row[1] . '" /> 
												<input type="hidden" name="itemID" value="' . $row[0] . '" /> 			
											</td>
											<td>  
												<label for="package"><b>Item Package</b></label>								
											</td>
											<td>
												<select id="itemPackage" name="itemPackage">
												<optgroup label="Item Package">';													
												//Connect to and query db
												$mysqli1 = new mysqli(HOST, USER, PASSWORD, DBNAME);
												if (mysqli_connect_errno()) 
												{
													echo "<p class='error'>Connect failed, please contact your system administrator</p>";
													exit();
												}
												if ($mysqli1->multi_query('call select_all(\'PACKAGE\', \'1\', \'1\');')) 
												{
													do 
													{
														if ($result1 = $mysqli1->store_result()) 
														{
															while ($row1 = $result1->fetch_row()) 
															{
															   if($row1[0] == $row[7])
																{
																	echo '<option selected="selected" value="' . $row1[0] . '">' . $row1[1] . '</option>';
																} else 
																{
																	echo '<option value="' . $row1[0] . '">' . $row1[1] . '</option>';
																}
															}
															$result1->close();
														}
													}while ($mysqli1->next_result());				  
												}
												else 
												{
												  $errorArray[] = 'Error, please contact your system administrator';
												}//end query if/else
												mysqli_close($mysqli1);
												
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
												<input type="text" name="itemQuantity" id="itemQuantity" value="' . $row[2] . '" />	
											</td>
											<td colspan="2"></td>
										</tr>
										<tr>
											<td>
												<label for="quantityAvail"><b>Quantity Available</b></label>	
											</td>
											<td>
												<input type="text" name="itemAvailable" id="itemAvailable" value="' . $row[3] . '" />
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
												//Connect to and query db
												$mysqli2 = new mysqli(HOST, USER, PASSWORD, DBNAME);
												if (mysqli_connect_errno()) 
												{
													echo "<p class='error'>Connect failed, please contact your system administrator</p>";
													exit();
												}
												if ($mysqli2->multi_query('call select_all(\'CATEGORY\', \'1\', \'1\');')) 
												{
													do 
													{
														if ($result2 = $mysqli2->store_result()) 
														{
															while ($row2 = $result2->fetch_row()) 
															{
																if($row2[0] == $row[5])
																{
																	echo '<option selected="selected" value="' . $row2[0] . '">' . $row2[1] . '</option>';
																} else 
																{
																	echo '<option value="' . $row2[0] . '">' . $row2[1] . '</option>';
																}															 
															}
															$result2->close();
														}
													}while ($mysqli2->next_result());				  
												}
												else 
												{
												  $errorArray[] = 'Error, please contact your system administrator';
												}//end query if/else
												mysqli_close($mysqli2);

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
						}
					  $result->close();
					}
				}while ($mysqli->next_result());
			}
			else 
			{
				$errorArray[] = 'Error, please contact your system administrator';
			}//end query if/else
			mysqli_close($mysqli);
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
								//Connect to and query db
								$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
								if (mysqli_connect_errno()) 
								{
									echo "<p class='error'>Connect failed, please contact your system administrator</p>";
									exit();
								}
								if ($mysqli->multi_query('call select_all(\'PACKAGE\', \'1\', \'1\');')) 
								{
									do 
									{
										if ($result = $mysqli->store_result()) 
										{
											while ($row = $result->fetch_row()) 
											{
											   echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
											}
											$result->close();
										}
									}while ($mysqli->next_result());				  
								}
								else 
								{
								  $errorArray[] = 'Error, please contact your system administrator';
								}//end query if/else
								mysqli_close($mysqli);
									
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
								//Connect to and query db
								$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
								if (mysqli_connect_errno()) 
								{
									echo "<p class='error'>Connect failed, please contact your system administrator</p>";
									exit();
								}
								if ($mysqli->multi_query('call select_all(\'CATEGORY\', \'1\', \'1\');')) 
								{
									do 
									{
										if ($result = $mysqli->store_result()) 
										{
											while ($row = $result->fetch_row()) 
											{
											   echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
											}
											$result->close();
										}
									}while ($mysqli->next_result());				  
								}
								else 
								{
								  $errorArray[] = 'Error, please contact your system administrator';
								}//end query if/else
								mysqli_close($mysqli);
					
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
		
		//Connect to and query db
		$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
		if (mysqli_connect_errno()) 
		{
			echo "<p class='error'>Connect failed, please contact your system administrator</p>";
			exit();
		}
		//edit/delete form
		if ($mysqli->multi_query("call select_all('all_items', 1, 1);")) 
		{
			// Table header:
			echo '<h2>Edit / Delete an Item</h2><div class="scrollBox">
				<form action="manageItems.php" method="POST" name="manageItemsForm">
				<table width="100%" cellspacing="0" cellpadding="2">';
			$bg = '#ebe3c3';
			do 
			{
				if ($result = $mysqli->store_result()) 
				{
					while ($row = $result->fetch_row()) 
					{
						$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
						echo '
						<tr bgcolor="' . $bg . '">
							<td width="260"><b>' . $row[0] . '</b></td>
							<td width="200">' . $row[4] . '</td>
							<td valign="right">
								<button class="buttons" name="itemEdit"	value="' . $row[1] . '"><img src="./images/edit.png" length="20" width="20" /></button>
								<button class="buttons" name="itemDelete" value="' . $row[1] . '" onclick="return confirmation()">
									<img src="./images/delete.png" /></button>
								<input type="hidden" name="submitted" value="2" />
							</td>
						</tr>';
					}
				  $result->close();
				}
			}while ($mysqli->next_result());
			echo '</td></tr></table></form></div>';
		}
		else 
		{
			$errorArray[] = 'Error, please contact your system administrator';
		}//end query if/else
		mysqli_close($mysqli);
		
		//Remove after all stored procedures are in
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
	}//end admin check
?>

