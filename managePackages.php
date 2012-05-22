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
		$page_title = 'MHIS - Manage Packages';
		include ('./includes/header.html');
		
		echo "<h1>Manage Packages</h1>
		<script type='text/javascript' language='JavaScript'>
			document.forms['createPackage'].elements['packageName'].focus();			
			function confirmation() 
			{
				if (confirm('Delete is permanent. Continue?')) 
				{
					return true;
				}
				else {
					return false;
				}
			}			
		</script>";
			
		// $pageType is used by the page to determine whether to display the add form or the edit form
		$pageType = 'add';
		$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: '.mysqli_connect_error());
		
		// 1 - add package
		// 2 - delete package
		// 3 - edit package
		// 4 - add category to package
		if (isset($_POST['submitted'])) 
		{
			$submitVal = $_POST['submitted'];
			$errorArray = array(); // error array
		
			// Stuff that needs to be checked for add & update
			if($submitVal == 1 || $submitVal == 3)
			{						
				$packageID = trim($_POST['packageID']);
				
				// Check for package name
				if (empty($_POST['packageName'])) 
				{
					$errorArray[] = 'You forgot to enter the package name.';
				} else {
					$packageName = mysqli_real_escape_string($dbc, trim($_POST['packageName']));
				}
			}
			
			if($submitVal == 1) // Add package
			{											
				//check for duplicate name
				if (empty($errorArray)) 
				{ 
					/*
					$q = "SELECT * FROM PACKAGE WHERE package_name = '$packageName';";	
					$r = @mysqli_query ($dbc, $q);
					$numRows = mysqli_num_rows($r);
					if ($numRows > 0) 
					{ 
						$errorArray[] = 'Package name already in use, please re-enter';
					}
					*/
					
					//Connect to and query db
					$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
					if (mysqli_connect_errno()) 
					{
						echo "<p class='error'>Connect failed, please contact your system administrator</p>";
						exit();
					}
					if ($mysqli->multi_query('call select_all(\'PACKAGE\', \'package_name\', \'' . "$packageName" . '\');')) 
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
							$errorArray[] = 'Package name already in use, please re-enter';
						}
					}
					else 
					{
						$errorArray[] = 'Error, please contact your system administrator';
					}//end query if/else
					mysqli_close($mysqli);
					
				}// end if errors
				
				// No errors, add new package
				if (empty($errorArray)) 
				{ 
					$query = "INSERT INTO PACKAGE (package_name) VALUES ('$packageName');";		
					$result = @mysqli_query ($dbc, $query);
					
					if ($result) 
					{
						echo "<p><b>$packageName has successfully been added.</b></p>";	
						
					} else 
					{
						echo '<p class="error"><b>System Error</b><br />
							Package could not be added due to a system error. We apologize for any inconvenience.</p>'; 								
					} // end of if ($result)				
				}// end of if empty	
				
			}//end of add package
			
			if($submitVal == 4) // add category to package
			{			
				// check for item limit and make sure it's a number
				if (empty($_POST['itemLimit'])) 
				{
					$errorArray[] = 'Must enter an item limit.';
					
				} else {
					$itemLimit = $_POST['itemLimit'];
					if (is_numeric($itemLimit) && $itemLimit > 0) 
					{} else {
						$errorArray[] = 'Invalid available quantity. Number must be greater than 0.';
						$itemLimit = NULL;
					}
				}
				
				$packageID = $_POST['packageID'];
				$categoryID = $_POST['categoryID'];
				//$allowDups = $_POST['allowDups'];
				
				// Need to make sure caterory isn't already in package
				if (empty($errorArray)) 
				{
					$query = "SELECT CATEGORY_ID, PACKAGE_ID FROM PACKAGE_ENTRY;"; // change to display_view(package_entry)
					$result = @mysqli_query ($dbc, $query);
					
					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
					{
						if($row['CATEGORY_ID'] == $categoryID && $row['PACKAGE_ID'] == $packageID)
						{
							$errorArray[] = 'Category already included in package.';
						}
					}
				}

				// No errors, add new package_entry
				if (empty($errorArray)) 
				{ 
					//I'm just going to set the package_entry name to the category name for now
					$query = "SELECT NAME FROM CATEGORY WHERE CATEGORY_ID = '$categoryID' LIMIT 1;"; // already covered in category procs
					$result = @mysqli_query ($dbc, $query);
					$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$cName = $row['NAME'];
					
					// Insert
					$query = "INSERT INTO PACKAGE_ENTRY (NAME, CATEGORY_ID, PACKAGE_ID, QUANTITY) VALUES 
						('$cName', '$categoryID', '$packageID', '$itemLimit');";		
					$result = @mysqli_query ($dbc, $query);
					
					if ($result) 
					{
						echo "<p><b>$cName has successfully been added.</b></p>";	
						
					} else 
					{
						echo '<p class="error"><b>System Error</b><br />
							Package could not be added due to a system error. We apologize for any inconvenience.</p>'; 								
					} // end of if ($result)				
				}// end of if empty	
				$pageType = 'edit';
			}//end of add category to package
			
			//package is to be deleted
			if(($_POST['packageDelete'] != NULL) && ($submitVal == 2))
			{
				$package = $_POST['packageDelete']; 
				
				//check that package is not empty first
				$q = "SELECT * FROM PACKAGE_ENTRY WHERE PACKAGE_ID = '$package';";	
				$r = @mysqli_query ($dbc, $q);
				$numRows = mysqli_num_rows($r);
				if ($numRows > 0) 
				{ 
					$errorArray[] = 'Package must be empty to delete';
				}
				
				if (empty($errorArray)) 
				{ 
					$query = "DELETE FROM PACKAGE WHERE PACKAGE.package_id = $package;";		
					$result = @mysqli_query ($dbc, $query);
					
					if ($result) 
					{
						echo "<p><b>Package has successfully been removed.</b></p>";	
						
					} else 
					{
						echo '<p class="error"><b>System Error</b><br />
							Package could not be removed due to a system error. We apologize for any inconvenience.</p>'; 							
					} // End of if ($result)
				}
			}// End of delete
			
			//category is to be deleted from package
			if(($_POST['categoryDelete'] != NULL) && ($submitVal == 2))
			{		
				$category = $_POST['categoryDelete']; 
				$query = "DELETE FROM PACKAGE_ENTRY WHERE PACKAGE_ENTRY.ID = $category;";		
				$result = @mysqli_query ($dbc, $query);
				
				if ($result) 
				{
					echo "<p><b>Category has successfully been removed.</b></p>";	
					
				} else 
				{
					echo '<p class="error"><b>System Error</b><br />
						Package could not be removed due to a system error. We apologize for any inconvenience.</p>'; 							
				} // End of if ($result)
				$pageType = 'edit';
				
			}// End of delete
			
			if($submitVal == 3 && !isset($_POST['cancelEdit'])) //edit package (post)
			{			
				// check for duplicate name (unless it's the item being edited) 
				if (empty($errorArray)) 
				{ 
					$q = "SELECT * FROM PACKAGE WHERE package_name = '$packageName';";	
					$r = @mysqli_query ($dbc, $q);
					$numRows = mysqli_num_rows($r);
					if ($numRows > 0) 
					{ 
						$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
						$newPack = $row['package_id'];
						if($newPack != $packageID)
						{
							$errorArray[] = 'Package name already in use, please re-enter';	
						}
					}
				}// end if errors
				
				// No errors, edit package
				if (empty($errorArray)) 
				{ 
					$query = "UPDATE PACKAGE SET package_name='$packageName' WHERE package_id='$packageID' LIMIT 1;";		
					$result = @mysqli_query ($dbc, $query);
					
					if ($result) 
					{
						echo "<p><b>$packageName has successfully been edited.</b></p>";
						unset($_POST); // clearing the post vars, or they show up in the add boxes when the page loads						
						
					} else 
					{
						echo '<p class="error"><b>System Error</b><br />
							Package could not be edited due to a system error. We apologize for any inconvenience.</p>'; 								
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
		// edit package form
		if(($_POST['packageEdit'] != NULL) || $pageType == 'edit')
		{
			if (isset($_POST['packageID']))
			{
				$package = $_POST['packageID'];
			}
			else
			{
				$package = $_POST['packageEdit'];
			}
			
			$query = "SELECT * FROM PACKAGE WHERE PACKAGE.package_id = $package;";		
			$r = @mysqli_query ($dbc, $query);
			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			
			echo '
			<form action="managePackages.php" method="POST" name="editPackagesForm">
				<fieldset class="forms">
					<legend><h2>Edit Package Name</h2></legend>									
					<table>
						<tr>
							<td>  
								<label for="name"><b>Package Name</b></label>								
							</td>
							<td>
								<input type="text" name="packageName" id="packageName" value="' . $row['package_name'] . '" /> 
								<input type="hidden" name="packageID" value="' . $row['package_id'] . '" /> 			
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="editPackage" value="Submit" />
								<input type="hidden" name="submitted" value="3" />						
							</td>
							<td><input type="submit" name="cancelEdit" value="Cancel" /></td>
						</tr>
					</table>
				</fieldset>
			</form>	
			<br /><br />
			
			<form action="managePackages.php" method="POST" name="addToPackage">
				<fieldset class="forms">
					<legend><h2>Add Categories to a Package</h2></legend>									
					<table>
						<tr>
							<td>
								<label for="category"><b>Add Category</b></label>								
							</td>
							<td>
								<select id="categoryID" name="categoryID">
								<optgroup label="Available Categories">';
						   
									$query = "SELECT CATEGORY_ID, NAME FROM CATEGORY ORDER BY NAME;"; // use display_view()	
									$r = @mysqli_query ($dbc, $query); 
									
									while ($row2 = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
									{
										echo '<option value="' . $row2['CATEGORY_ID'] . '">' . $row2['NAME'] . '</option>';
										
									}//end while
								echo '        
								</optgroup>
								</select>																
							</td>					
							<td>
								<label for="package"><b>To Package</b></label>								
							</td>
							<td>
								<select id="packageID" name="packageID">
								<optgroup label="Available Packages">';								
									$query = "SELECT * FROM PACKAGE ORDER BY package_name;"; // use display_view()	 	
									$r = @mysqli_query ($dbc, $query); 
									
									while ($row3 = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
									{
										if($row3['package_id'] == $package)
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
								<b>Item Limit</b>								
							</td>
							<td>
								<input type="text" name="itemLimit" id="itemLimit" size="5" value="" />									
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="addToPack" value="Add Category" />
								<input type="hidden" name="submitted" value="4" />						
							</td>
							<td></td>
						</tr>
					</table>
				</fieldset>
			</form><br />';
			
			// Edit / Delete Form 
			$q = "SELECT * FROM PACKAGE_ENTRY WHERE PACKAGE_ENTRY.PACKAGE_ID = $package ORDER BY NAME;";	
			$r = @mysqli_query ($dbc, $q);
			$numRows = mysqli_num_rows($r);
			
			if ($numRows > 0) 
			{ 
				// Table header:
				echo '<h2>Remove categories from package</h2><div class="border">
					<form action="managePackages.php" method="POST" name="removeCategoriesForm">
					<table width="100%" cellspacing="0" cellpadding="2">';
			
				$bg = '#ebe3c3';
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
				{
					$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
					echo '
					<tr bgcolor="' . $bg . '">
						<td width=80%><b>' . $row['NAME'] . '</b></td>
						<td valign="right">
							<button class="buttons" name="categoryDelete" value="' . $row['ID'] . '" onclick="return confirmation()">
								<img src="./images/delete.png" /></button>
							<input type="hidden" name="submitted" value="2" />
							<input type="hidden" name="packageID" value="' . $package . '" />
						</td>
					</tr>';
				}	
				echo '</td></tr></table></form></div><br /><br />';

			} else { // If no packages were returned
				echo '<p class="error">There are currently no categories assigned to this package.</p>';
			}
			
			exit();	
		}// end of edit form		

		if($pageType == 'add')
		{
			echo '
			<form action="managePackages.php" method="POST" name="createPackage">
				<fieldset class="forms">
					<legend><h2>Create a Package</h2></legend>									
					<table>
						<tr>
							<td>  
								<label for="name"><b>Package Name</b></label>								
							</td>
							<td>
								<input type="text" name="packageName" id="packageName" value="" /> 			
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="createPack" value="Create Package" />
								<input type="hidden" name="submitted" value="1" />						
							</td>
							<td></td>
						</tr>
					</table>
				</fieldset>
			</form><br />';	
			
			echo '<br /><br />';	
			
			// Edit / Delete Form 
			$q = 'SELECT * FROM PACKAGE ORDER BY package_name;'; // use display_view()	
			$r = @mysqli_query ($dbc, $q);
			$numRows = mysqli_num_rows($r);
			if ($numRows > 0) 
			{ 
				// Table header:
				echo '<h2>Edit / Delete a Package</h2><div class="scrollBox">
					<form action="managePackages.php" method="POST" name="managePackagesForm">
					<table width="100%" cellspacing="0" cellpadding="2">';
			
				$bg = '#ebe3c3';
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
				{
					//I'm excluding default package from edits
					if($row['package_id'] != 1)
					{
						$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
						echo '
						<tr bgcolor="' . $bg . '">
							<td width=80%><b>' . $row['package_name'] . '</b></td>
							<td valign="right">
								<button class="buttons" name="packageEdit"	value="' . $row['package_id'] . '">
									<img src="./images/edit.png" length="20" width="20" /></button>
								<button class="buttons" name="packageDelete" value="' . $row['package_id'] . '" onclick="return confirmation()">
									<img src="./images/delete.png" /></button>
								<input type="hidden" name="submitted" value="2" />
							</td>
						</tr>';
					}
				}
				
				echo '</td></tr></table></form></div>';
			} else { // If no packages were returned
				echo '<p class="error">There are currently no packages.</p>';
			}
		}// end add
		
		mysqli_free_result ($r);
		mysqli_close($dbc);
		//footer:
		include ('./includes/footer.html');
		
		echo "
			<script type='text/javascript' language='JavaScript'>
				document.forms['createPackage'].elements['packageName'].focus();					
			</script>";
	}//end admin check
?>

