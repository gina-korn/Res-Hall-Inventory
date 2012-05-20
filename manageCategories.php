<?php
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - Manage Categories';
	include ('./includes/header.html');
	
	echo '<h1>Manage Categories</h1>';

	// $pageType is used by the page to determine whether to display the add form or the edit form
	$pageType = 'add';
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: '.mysqli_connect_error());
	
	if (isset($_POST['submitted'])) 
	{
		$submitVal = $_POST['submitted'];
		$errorArray = array(); // error array
	
		// Check for errors
		if($submitVal == 1 || $submitVal == 3)
		{
			// Check for item name
			if (empty($_POST['categoryName'])) 
			{
				$errorArray[] = 'You forgot to enter the category name.';
			} else {
				$categoryName = mysqli_real_escape_string($dbc, trim($_POST['categoryName']));
			}
			
			// Check for checkout length
			if (empty($_POST['checkoutLength'])) 
			{
				$errorArray[] = 'You forgot to enter a checkout length.';
				
			} else {
				$checkoutLength = $_POST['checkoutLength'];
				if (is_numeric($checkoutLength) && $checkoutLength > 0) 
				{} else {
					$errorArray[] = 'Invalid available checkout length, must be a number greater than 0.';
					$checkoutLength = NULL;
				}
			}
			
			$catID = trim($_POST['catID']);
		}
		
		if($submitVal == 1) // Add category
		{					
			
			// I'm just making sure there isn't already a category with the same name
			// but this check only needs to be made if a valid name has been entered 
			if (empty($errorArray)) 
			{ 
				//Connect to and query db
				$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
				if (mysqli_connect_errno()) 
				{
					echo "<p class='error'>Connect failed, please contact your system administrator</p>";
					exit();
				}
				if ($mysqli->multi_query('call select_all(\'CATEGORY\', \'NAME\', \'' . "$categoryName" . '\');')) 
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
						$errorArray[] = 'Category name already in use, please re-enter';
					}				  
				}
				else 
				{
				  //printf("<br />Error: %s\n", $mysqli->error);
				  $errorArray[] = 'Error, please contact your system administrator';
				}//end query if/else
				mysqli_close($mysqli);
				
			}// end if errors
			
			// No errors, add new category
			if (empty($errorArray)) 
			{ 
				$query = "INSERT INTO CATEGORY (NAME, checkout_length) VALUES ('$categoryName', '$checkoutLength');";		
				$result = @mysqli_query($dbc, $query);
				
				if ($result) 
				{
					echo "<p><b>$categoryName has successfully been added.</b></p>";	
					
				} else 
				{
					echo '<p class="error"><b>System Error</b><br />
						Category could not be added due to a system error. We apologize for any inconvenience.</p>'; 								
				} // end of if ($result)				
			}// end of if empty	
			
		}//end of $submitVal == 1
		
		
		// Category is to be deleted
		if(($_POST['catDelete'] != NULL) && ($submitVal == 2))
		{
			$catID = $_POST['catDelete'];
			
			// Need to make sure category is empty before delete
			//Connect to and query db
			$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
			if (mysqli_connect_errno()) 
			{
				echo "<p class='error'>Connect failed, please contact your system administrator</p>";
				exit();
			}
			if ($mysqli->multi_query('call select_all(\'ITEM\', \'CATEGORY_ID\', \'' . "$catID" . '\');')) 
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
					$errorArray[] = 'Error! Category must be empty, please remove items first.';
				}
			}
			else 
			{
				//printf("<br />Error: %s\n", $mysqli->error);
				$errorArray[] = 'Error, please contact your system administrator';
			}//end query if/else
			mysqli_close($mysqli);
		
			// No errors, delete category
			if (empty($errorArray)) 
			{
				//Need to make a stored procedure for this
				$query = "DELETE FROM PACKAGE_ENTRY WHERE CATEGORY_ID = $catID;";
				$result = @mysqli_query ($dbc, $query);				
				$query = "DELETE FROM CATEGORY WHERE CATEGORY_ID = $catID;";		
				$result1 = @mysqli_query ($dbc, $query);
				
				if ($result && $result1) 
				{
					echo "<p><b>Category has successfully been removed.</b></p>";	
					
				} else 
				{
					echo '<p class="error"><b>System Error</b><br />
						Category could not be removed due to a system error. We apologize for any inconvenience.</p>'; 							
				} // End of if ($result)
				
			}// end error check
			
			
		}// End of delete
		
		if($submitVal == 3) //edit category (post)
		{							
			if (empty($errorArray)) 
			{ 			
				//Connect to and query db
				$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
				if (mysqli_connect_errno()) 
				{
					echo "<p class='error'>Connect failed, please contact your system administrator</p>";
					exit();
				}
				//because I'm doing an edit, cat name will always already be in use, but we don't want 2 categories w/ the same name...
				if ($mysqli->multi_query('call select_all(\'CATEGORY\', \'NAME\', \'' . "$categoryName" . '\');')) 
				{
					$num = 0;
					do 
					{
						if ($result = $mysqli->store_result()) 
						{
							while ($row = $result->fetch_row()) 
							{
								$newCat = $row[0];
								// ...so I'm checking the original category ID against that of the 
								// new category name, if they are different error is thrown
								// but if they are the same, the edit can go forward
								if($newCat != $catID)
								{
									$errorArray[] = 'Category name already in use, please re-enter';	
								}
							}
							$result->close();
						}
					}while ($mysqli->next_result());
				}
				else 
				{
					//printf("<br />Error: %s\n", $mysqli->error);
					$errorArray[] = 'Error, please contact your system administrator';
				}//end query if/else
				mysqli_close($mysqli);

			}// end if errors
			
			// No errors, edit category
			if (empty($errorArray)) 
			{ 
				$query = "UPDATE CATEGORY SET CATEGORY.NAME='$categoryName', checkout_length='$checkoutLength' 
					WHERE CATEGORY_ID='$catID' LIMIT 1;";		
				$result = @mysqli_query ($dbc, $query);
				
				if ($result) 
				{
					echo "<p><b>$categoryName has successfully been edited.</b></p>";
					unset($_POST); // clearing the post vars, or they show up in the add boxes when the page loads						
					
				} else 
				{
					echo '<p class="error"><b>System Error</b><br />
						Category could not be added due to a system error. We apologize for any inconvenience.</p>'; 								
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

/////// Forms ////////////
	// edit item form
	if(($_POST['catEdit'] != NULL) || $pageType == 'edit')
	{
		if (isset($_POST['catID']))
		{
			$catID = $_POST['catID'];
		}
		else
		{
			$catID = $_POST['catEdit'];
		}
	
		//Connect to and query db
		$mysqli = new mysqli(HOST, USER, PASSWORD, DBNAME);
		if (mysqli_connect_errno()) 
		{
			echo "<p class='error'>Connect failed, please contact your system administrator</p>";
			exit();
		}
		if ($mysqli->multi_query('call select_all(\'CATEGORY\', \'CATEGORY_ID\', \'' . "$catID" . '\');')) 
		{
			$num = 0;
			do 
			{
				if ($result = $mysqli->store_result()) 
				{
					while ($row = $result->fetch_row()) 
					{
						echo '
						<form action="manageCategories.php" method="POST" name="editCategoryForm">
							<fieldset class="forms">
								<legend><h2>Edit</h2></legend>									
								<table>
									<tr>
										<td>  
											<label for="name"><b>Category Name</b></label>								
										</td>
										<td>
											<input type="text" name="categoryName" id="categoryName" value="' . $row[1] . '" /> 
											<input type="hidden" name="catID" value="' . $row[0] . '" /> 			
										</td>
									</tr>
									<tr>
										<td>
											<label for="quantityAvail"><b>Checkout Length (number of days)</b></label>	
										</td>
										<td>
											<input type="text" name="checkoutLength" id="checkoutLength" value="' . $row[3] . '" />	 
											(Leave blank for unlimited)
										</td>
									</tr>
									<tr>
										<td>
											<input type="submit" name="editCat" value="Submit Changes" />
											<input type="hidden" name="submitted" value="3" />						
										</td>
										<td></td>
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
			//printf("<br />Error: %s\n", $mysqli->error);
			$errorArray[] = 'Error, please contact your system administrator';
		}//end query if/else
		mysqli_close($mysqli);
		exit();	
		
	}// end of edit form		

	if($pageType == 'add')
	{
		echo '
		<form action="manageCategories.php" method="POST" name="addCatForm">
			<fieldset class="forms">
				<legend><h2>Add a Category</h2></legend>									
				<table>
					<tr>
						<td>  
							<label for="name"><b>Category Name</b></label>								
						</td>
						<td>
							<input type="text" name="categoryName" id="categoryName" value="';
							if(isset($_POST['categoryName'])) echo $_POST['categoryName'];
							echo '" /> 			
						</td>
					</tr>
					<tr>
						<td>
							<label for="checkout"><b>Checkout Length (number of days)</b></label>	
						</td>
						<td>
							<input type="text" name="checkoutLength" id="checkoutLength" value="';
							if(isset($_POST['checkoutLength'])) { echo $_POST['checkoutLength']; } else { echo'1'; }
							echo '" />
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="addCat" value="Add Category" />
							<input type="hidden" name="submitted" value="1" />						
						</td>
						<td></td>
					</tr>
				</table>
			</fieldset>
		</form>';
	}//end add form
	
	echo '<br /><br />';	
	
	// Edit / Delete Form 
	$q = 'SELECT * FROM CATEGORY ORDER BY NAME;';//will the select_all proc work here if I leave out the match?	
    $r = @mysqli_query ($dbc, $q);
	$numRows = mysqli_num_rows($r);
	if ($numRows > 0) 
	{ 
		// Table header:
		echo '<h2>Edit / Delete a Category</h2><div class="scrollBox">
			<form action="manageCategories.php" method="POST" name="manageCategoriesForm">
			<table width="100%" cellspacing="0" cellpadding="2">';
	
		$bg = '#ebe3c3';
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
		{
			$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
			
			echo '
			<tr bgcolor="' . $bg . '">
				<td width="260"><b>' . $row['NAME'] . '</b></td>
				<td valign="right">
					<button class="buttons" name="catEdit"	value="' . $row['CATEGORY_ID'] . '">
						<img src="./images/edit.png" length="20" width="20" /></button>
					<button class="buttons" name="catDelete" value="' . $row['CATEGORY_ID'] . '" onclick="return confirmation()">
						<img src="./images/delete.png" /></button>
					<input type="hidden" name="submitted" value="2" />
				</td>
			</tr>';
		}
	
		echo '</td></tr></table></form></div>';
		
		mysqli_free_result ($r);
	
	} else { // If no categories were returned
		echo '<p class="error">There are currently no categories.</p>';
	}

	mysqli_close($dbc);
	//footer:
	include ('./includes/footer.html');
	
	echo '
		<script type="text/javascript" language="JavaScript">
			document.forms["addCatForm"].elements["categoryName"].focus();			
			function confirmation() 
			{
				if (confirm("This will permanantly remove category. Continue?")) 
				{
					return true;
				}
				else {
					return false;
				}
			}
		</script>';
?>

