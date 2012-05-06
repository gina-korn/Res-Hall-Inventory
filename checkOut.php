<?php
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}
	
	$page_title = 'MHIS - Item Check-Out';
	include ('includes/header.html');
	
	echo '<h1>Item Check-Out</h1>';
	echo '<p>This is an "under development" version of checkout, has a few post-checkout issues I\'m working on<p>';
	
	//clear order
	if(isset($_GET['Reset_Order_Action']))
	{	
		clearSession();
	}
	
	if(isset($_GET['confirmed_Item']))
	{	
		$_SESSION['showItem'] = true;
	}					
	
	
	// --------The order form for the current check out---------
	echo '
	<fieldset class="forms" >
		<legend><h2>Check Out Order</h2></legend>	
		<form action = "checkOut.php" method = "GET" name = "CheckOut">					
		<table>
			<tr>
				<td>  
					<label for="name"><b>Office Worker: </b></label>								
				</td>
				<td>
					';
					// ----------grabbing the information of the current Office Worker-----------
						
						// the SESSION variable $_SESSION['OWName'] is set in the Auth class method login
						// it will always be set if an office worker is logged in
						if(isset($_SESSION['OWName']))
						{
							$workerName = $_SESSION['OWName'];
							echo $workerName;
						}
					echo'
				</td>
			</tr>
			<tr>
				<td>
					<label for="CA"><b>Resident: </b></label>	
				</td>
				<td>
					';//print statement for Resident Name
					if(isset($_GET['confirm_Resident']) || $_SESSION['confirm_Resident'] == true )
					{
						$_SESSION['confirm_Resident'] = true;
						$F_NAME = $_SESSION['$F_NAME'];
						$L_NAME = $_SESSION['$L_NAME'];
						echo $F_NAME . " " . $L_NAME;
					}
					echo'
				</td>
			</tr>
			<tr>
				<td>
			
					<label for="CA"><b>Item: </b></label>	
				</td>
				<td>
					';//print statement for Item. 
					$showItem = $_SESSION['showItem'];
					if((isset($_GET['confirm_Item']) || isset($_SESSION['item_Name'])) && $showItem != false)
					{
						$item_Name = $_SESSION['item_Name'];
						echo $item_Name;
					}
					echo'
				</td>
			</tr>
			<tr align="right">
				<td>
					<input type="submit" name="Reset_Order_Action" value="Clear Order" />
				</td>
				<td>
				';if(isset($_GET['confirm_Item']) )
					{
					echo'
					<input type="submit" name="Checkout_Order_Action" value="Check Out" />';
					}
				echo'	
				</td>
			</tr>
		</table>
		<input name = "CheckOut" type= "hidden" Value = "CheckOut">
		</form>
	</fieldset><br />'; // end Check Out Order form


	//-------- Handeling Resident Information--------------
	if($_SESSION['confirm_Resident']  == false || $_SESSION['clearPage'] == true)
	{	
		unset($_SESSION['clearPage']);
		//this is the form to grab the resident's information based off of their student ID		
		echo'
			<form action="checkOut.php" method="GET" name="getResident">
				<fieldset class = "forms">
					<label for="name">Student ID </label><br /> <input type="text" name="resident_id" id="resident_id" value="';
						if(isset($_GET['name'])) echo $_GET['name']; echo'" />
					<input type="submit" name="submit" value="Find Resident" />
					<input type="hidden" name="submitting_resident_name" value="1" />	
				</fieldset>
			</form>
			';
		
		//sets the auto focus to the form above it. fill in form name and text box name
		echo '<script type="text/javascript" language="JavaScript">
					document.forms["getResident"].elements["resident_id"].focus();
				</script>';	
				
	}// end confirm_Resident == false


	//-------- Grabbing resident information from the database---------

	// $_GET['submitting_resident_name'] this comes from the getResident form, and is the resident student id.	
	// $_GET['confirmed_Resident'] comes from when the office worker clicks OK to a residents name
	if(isset($_GET['submitting_resident_name']) && !(isset($_GET['confirmed_Resident'])) )
	{
		//Parse the ID number entered. Student ID's are eight digits. The only reason that a valid number would be
		//longer than eight is if a Student ID Card is swiped through a scanner. A lot of junk is attatched.
		$resident_id = $_GET['resident_id'];
		$strlen =  strlen($resident_id);
		if($strlen > 8)
		{
			$arr = str_split($resident_id, 1);
			$resident_id = '';
			
			for($i = 0; $i < $strlen; $i++)
			{
				if($i > 1 && $i < 10)
				{
					echo $arr[i];
					$resident_id = $resident_id . $arr[$i];
				}
			}		
		}

		if(is_numeric($resident_id))
		{
			$resident_id = md5($resident_id);
			
			// Make the connection:
			$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
			// Make the query:
			$q = "SELECT * FROM USER WHERE STUDENT_ID = " . '"' . $resident_id .'"';	//NEED STORED PROCEDURE	
			$r = mysqli_query ($dbc, $q);
			
			$num = @mysqli_num_rows($r);
				
			if ($num > 0) //if result returned, continue with adding user 
			{ 
				while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
				{
					$student_id = $resident_id;;
					$F_NAME = $row['F_NAME'];
					$L_NAME = $row['L_NAME'];
					$ROOM_NUMBER =  $row['ROOM_NUMBER'] ;
					$eligible = $row['AVAILABLE_CO'] ;
					$admin = $row['admin'] ;
					
					$_SESSION['sid'] = $row['SID'];
					$_SESSION['$STUDENT_ID'] = $student_id;
					$_SESSION['$F_NAME'] = $F_NAME;
					$_SESSION['$L_NAME'] = $L_NAME;
					$_SESSION['$ROOM_NUMBER'] = $ROOM_NUMBER;
					$_SESSION['$eligible'] = $eligible;
					$_SESSION['$admin'] = $admin;
				}
						
				mysqli_free_result ($r); // Free up the resources.	
				mysqli_close($dbc); // Close the database connection.
				
				//---------- Display and confirm the user that matches the resident_ID entered---------------
				echo '
				<br />
					<form action="checkOut.php" method="GET">
						<fieldset class="forms" >				
							<table>
								<tr>
									<td>  
										<label for="name">Add </label>								
									</td>
									<td><b>';
										echo $F_NAME . " " . $L_NAME ; echo'</b> to checkout order?
									</td>
									<td>
										';
										if($eligible > 0)
										{
										echo'
											<input type="submit" name="confirmed_Resident" value="Add Resident" />
											<input type="hidden" name="confirm_Resident" value="1" />
											';
										}
										else
										{
											echo '<p class="error"> ' . $F_NAME . " " . $L_NAME . ' already has an item checked out..</p>';
										}
										echo '
									</td>
								</tr>
							</table>	
						</fieldset>
					</form>
				<br />';
			} else { // If no records were returned.
				echo '<p class="error">No user exists with that ID</p>';		
			}
		}
		else { //resident id was non-numeric
			echo '<p class="error">User ID must be a number</p>';
		}// end numeric id check
		
	}// end submitting_resident_name
	
	
	//---------display the form to get the first Item------------------							
	if((isset($_GET['confirm_Resident']) || $_SESSION['confirmed_Resident'] == true) || ($_SESSION['displayItemSearch'] == true))
	{
		$_SESSION['confirmed_Resident'] = true;
		
		$sid = $_SESSION['sid'];
		$resident_id = $_SESSION['$STUDENT_ID'];
		$resident_name = $_SESSION['$F_NAME'] . " " . $_SESSION['$L_NAME'];
		$eligible = $_SESSION['$eligible'];
		$admin = $_SESSION['$admin'];
			
		if($eligible == 1)
		{
			//connect to db and query for overdue items
			$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
//NEED STORED PROCEDURE	
			$q = "SELECT * FROM past_due_report WHERE 'Student ID' = " . '"' . $resident_id .'"';
			$r = @mysqli_query ($dbc, $q);
			$num = mysqli_num_rows($r);
			
				
			if ($num > 0) //if result returned, resident has overdue items
			{ 
				echo "<p class='error'>$resident_name currently has past due items which must be checked in first.</p>";
				$_SESSION['remainingCheckouts'] = "none";
				$_SESSION['displayItemSearch'] = false;
			}
			else
			{
				//echo 'No overdue items.<br />';
// check for admin status ********************** This should also check for CA status ****************************
				if($admin == 1)
				{
					echo "<p>" . $F_NAME . " " . $L_NAME . " is an Administrator and can checkout unlimited items.</p>";
					$_SESSION['remainingCheckouts'] = "any";					
				} 
				else 
				{
//NEED STORED PROCEDURE
//echo $sid;
					//if not an admin, need to see whether resident has items checked out
					$q = "SELECT ITEM.NAME AS INAME, CATEGORY.NAME AS CNAME, CATEGORY_ID, package_name, PACKAGE_ID 
						FROM (((CHECKOUT JOIN LINEITEM USING (ORDER_NUMBER)) JOIN ITEM USING(ITEM_ID)) 
						JOIN PACKAGE USING(PACKAGE_ID)) JOIN CATEGORY USING (CATEGORY_ID) WHERE 
						(STUDENT_ID = '$sid') AND (DATE_CHECKED_IN IS NULL);";
					$r = mysqli_query ($dbc, $q);
					$num = mysqli_num_rows($r);

					
//echo "Number of returned rows: " . $num;
						
					if ($num > 0) //if user has item(s) checked out, use packages to figure out what additional items they can check out 
					{ 
						// this is only to check whether the first item is the one being displayed as a package owner
						$count = 0;
						
						//This cycles through items checked out by user
						while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
						{						
							$count++; 
							$iName = $row['INAME'];
							$cName = $row['CNAME'];
							$categoryID = $row['CATEGORY_ID'];
							$pName = $row['package_name'];
							$pID = $row['PACKAGE_ID'];
							
							//1st item, display package info and additional item checkout info
							if($count == 1)
							{
								echo "<p><b>$resident_name currently has the following items checked out:</b></p>";
								echo "<p><b>$count.) $iName</b> from the package $pName";  
//NEED STORED PROCEDURE								
								$q2 = "SELECT * FROM PACKAGE_ENTRY JOIN PACKAGE USING(PACKAGE_ID) WHERE PACKAGE_ENTRY.PACKAGE_ID = $pID;";	
								$r2 = @mysqli_query ($dbc, $q2);
								$num2 = mysqli_num_rows($r2);
								
								
								if ($num2 > 0) //the package contains package entries, which are listed
								{
									echo ", which allows the following additional checkouts: <ul>";
									//This cycles through the categories / amounts allowed by the package
									while ($row2 = mysqli_fetch_array($r2, MYSQLI_ASSOC)) 
									{
										$cName = $row2['NAME'];
										$cID = $row2['CATEGORY_ID'];
										$cQuant = $row2['QUANTITY'];
										echo "<li>$cQuant item(s) from category $cName.</li>";
									
										// Store the allowed category ID & quantity for future use
										$allowedArray[] = $cID;
										$allowedArray[] = $cQuant;										
									}
									echo "</ul></p>";
									
									//A list of all items in allowed categories would be nice here?
									
														
								} else //package contains no entries
								{ 
									echo ", no additional checkouts permitted</p>";
									$_SESSION['remainingCheckouts'] = "none";
									$_SESSION['displayItemSearch'] = false;
								}
							} else //additional items, just display the item name and package it is from
							{
								echo "<p><b>$count.) $iName</b> checked out from category $cName</p>";
								$checkedOutArray[] = $categoryID;
												
							}//end check for first item
											
						}//end while (items from checkout)
						
//For testing purposes
//echo "Allowed array: ";
//print_r($allowedArray);
//echo "<br />Checked-out array: ";
//print_r($checkedOutArray);
//echo "<br />displayItemSearch: " . $_SESSION['displayItemSearch'];
						
						if($count > 0 && $_SESSION['remainingCheckouts'] != "none" && $_SESSION['displayItemSearch'] != false)
						{
							//allowedArray contains pairs of numbers, a category ID followed by a quantity
							//Here the quantity is being decremented according to what has already been checked out
							for($i = 0; $i < count($allowedArray); $i++)
							{
								if($i % 2 == 0)
								{
									for($j = 0; $j < count($checkedOutArray); $j++)
									{
										if($allowedArray[$i] == $checkedOutArray[$j])
										{
											$allowedArray[$i + 1]--;
										}//end inner if
									}//end inner for
								}//end outer if
							}//end outer for
							
//echo "<br />Allowed array, in loop: ";
//print_r($allowedArray);
							$_SESSION['remainingCheckouts'] = implode('#', $allowedArray);
						}//end if
					
					} else 
					{
						echo "<p>$resident_name can currently check out 1 item</p>";
						$_SESSION['remainingCheckouts'] = "any";						
					}
				}//end admin check		
			}//end overdue check
		} else 
		{ //user not eligible
		
			echo '<p class="error">This user currently has a hold on their account, please contact system administrator.</p>';
			$_SESSION['remainingCheckouts'] = "none";
			$_SESSION['displayItemSearch'] = false;			
		
		}//end eligible check
	
		//if there are no "errors", present user with item checkout form (user allowed to checkout additional items)
		if($_SESSION['remainingCheckouts'] != "none" || $_SESSION['displayItemSearch'] == true)
		{
			//Grabs item based off of item name
			echo'
			<form action="checkOut.php" method="GET" name = "getItemName">
				<fieldset class="forms">
					<label for="item">Item Name: </label> <input type="text" name="item" id="item_name" value="';
						if(isset($_GET['item'])) echo $_GET['item']; echo'" />
					<input type="submit" name="submit" value="Find Item" />
					<input type="hidden" name="getItem" value="1" />
				</fieldset>
			</form>';
			
			//sets the auto focus to the form above it. fill in form name and text box name
			echo '
			<script type="text/javascript" language="JavaScript">
				document.forms["getItemName"].elements["item"].focus();
			</script>';
			
		} else { //the user is not allowed to check out any more items
				
			// for now, just do nothing
		}
		
			  
	}// end find item form	
		
		
	// ---------Adding an Item to Order---------------				
	if((isset($_GET['getItem']) || $_SESSION['getItem'] == true) && !(isset($_GET['confirm_Item'])) && !(isset($_GET['Checkout_Order_Action'])) )
	{
		$_SESSION['getItem'] = true;
		
		// Make the connection:
		$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
		
		//get the item name from the form getItemName
		$itemName = mysqli_real_escape_string($dbc, trim($_GET['item']));

		// Make the query:
		$q = "SELECT ITEM_ID, NAME, QUANTITY, AVAILABLE, CATEGORY_ID FROM ITEM WHERE NAME = " . "'". "$itemName" ."'";	//NEED STORED PROCEDURE	
		$r = @mysqli_query ($dbc, $q); // Run the query.
		$num = mysqli_num_rows($r);
				
		if ($num > 0)  // If result was returned:
		{
			$itemAllowed = 0; //0 for not allowed (default)
			while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
			{
				$ITEM_ID = $row['ITEM_ID'];
				$NAME = $row['NAME'];
				$QUANTITY = $row['QUANTITY'];
				$AVAILABLE = $row['AVAILABLE'];
				$CATEGORY_ID = $row['CATEGORY_ID'];
				
			} // End of WHILE loop.
						
			mysqli_free_result ($r); // Free up the resources.	
			mysqli_close($dbc); // Close the database connection.
					
			//Check to make sure user is allowed to check out additional items
			if($_SESSION['remainingCheckouts'] == "none")
			{
				echo "<p class='error'>This user cannot check out any additional items.</p>";
				$_SESSION['displayItemSearch'] = false;
			}
			else
			{
				$_SESSION['displayItemSearch'] = true;
				if($_SESSION['remainingCheckouts'] != "any")
				{
					//Here, the current item's category is being checked against user's 'allowed' categories
					$allowedArray = explode('#', $_SESSION['remainingCheckouts']);
					
//echo "<br />allowedItems (post): ";
//print_r($allowedArray);
					for($i = 0; $i < count($allowedArray); $i++)
					{
						if($i % 2 == 0)
						{
							if(($allowedArray[$i] == $CATEGORY_ID) && ($allowedArray[$i + 1] > 0))
							{
								$itemAllowed = 1;
							}//end if	
						}//end if
					}//end for				
				}
				else //user can check out an item from any category
				{
					$itemAllowed = 1;
				}
				
				if($itemAllowed == 1)
				{
				
					//if user is allowed to check item out
					$_SESSION['item_Name'] = $NAME;	
					$_SESSION['$ITEM_ID'] = $ITEM_ID;
					
					//-------------- Have office worker confirm, right item--------------------
					echo "
					<br />
						<form action='checkOut.php' method='GET'>
							<fieldset class='forms'>				
								<table>
									<tr>
										<td>  
											<b>Item: </b>								
										</td>
										<td> $NAME </td>
										<td>  
											<b>Available for Check Out:</b>								
										</td>
										<td> $AVAILABLE </td>
										<td>";
											// item availability
											if($AVAILABLE > 0)
											{
												echo'
												<input type="submit" name="confirmed_Item" value="Add Item" />
												<input type="hidden" name="confirm_Item" value="1" />';
											}
											else
											{
												echo '<p class="error"> ' . $NAME . ' is not currently available.</p>';
												$_SESSION['showItem'] = false;
											}
											echo "
										</td>
									</tr>
								</table>	
							</fieldset>
						</form>
					<br />";
				}
				else
				{
					echo "<p class='error'>Item checkout not allowed from this category</p> <i>(should include list of valid categories or items here)</i>";
					$_SESSION['showItem'] = false;
				}
			}//end remainingCheckouts else
		}
		else // If no records were returned.
		{ 
			echo '<p class="error">* No item exists with that name.</p>';
		}	
	}//end add item

										//-------------Checking Out Item---------------------
	if(isset($_GET['Checkout_Order_Action']) && isset($_SESSION['$ITEM_ID']))
	{
		$UserAction = $_GET['Checkout_Order_Action'];
		
		if($UserAction == 'Check Out')
		{
				// Make the connection:
				$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

				$date = date('y/d/m h:i:s ', time());
				$trueDate = date('y-m-d h:i:s ', time());
				$trueDate = '20' . $trueDate;

				$dueDate = strtotime ( '+1 day' , strtotime ( $date ) ) ;
				$dueDate = date('y-d-m h:i:s ', $dueDate);
				
				$pieces = explode(" ", $dueDate);
				$pDate =  $pieces[0]; // piece1
				$pTime = $pieces[1]; // piece2
				
				$pieces = explode("-", $pDate);
				$pMonth =  $pieces[0]; // piece1
				$pDay= $pieces[1]; // piece2
				$pyear =  $pieces[2]; // piece1
				$TrueDueDate = $pyear . '-' . $pMonth .'-' .$pDay . " " . $pTime;
				$TrueDueDate = '20' . $TrueDueDate;
				$studentID = $_SESSION['$STUDENT_ID'];
//echo $studentID; //**************************************************************************************************
				$OW_ID = $_SESSION['OW_ID'];
				$item_ID = $ITEM_ID = $_SESSION['$ITEM_ID'];
				
				$admin = $_SESSION['$admin'];
				$orderNum = 0;
		
				//NEED STORED PROCEDURE
				$q = "SELECT MAX(ORDER_NUMBER) + 1  AS 'ORDER_NUM' FROM CHECKOUT";
				$r =  @mysqli_query($dbc, $q);
				
				$num = mysqli_num_rows($r);
				
				if ($num > 0) // If it ran OK, display the records.
				{ 
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) 
					{
						$orderNum = $row['ORDER_NUM']	;	
					}
				}
				
				mysqli_free_result ($r); // Free up the resources.	
				$type = 'checkout';
								
				echo 'orderNum' . $orderNum . "<br />studentID" . $studentID . "<br />itemID " . $item_ID; 
				
				$q = "call check_out($orderNum ," . '"' . $resident_id .'"' . ", $item_ID);";
				$r =  @mysqli_query($dbc, $q);
				
				//$fail = $r;
				if(!$r)
				{
					echo '<p class = "error">Check out failed, please contact your system administrator.</p>';
					$_SESSION['displayItemSearch'] = false;
				}
				else
				{
					echo '<p>Check out Successfull</p>';
					$_SESSION['displayItemSearch'] = true;
				}
				
				//mysqli_free_result ($r); // Free up the resources.	
				mysqli_close($dbc); // Close the database connection.
				clearItemInfo();
				//This forces a page refresh, so that the checked out item shows up on the page without the user needing to do a 
				//manual refresh
				echo "<script language=javascript>window.location.reload()</script>";
				

		}
		//echo $nameSet;
	}	

include ('./includes/footer.html');

function clearItemInfo()
{
	unset($_SESSION['getItem']);
	unset($_SESSION['item_Name']);	
	unset($_SESSION['showItem']);
	unset($_SESSION['$ITEM_ID']);
	unset($_GET['Checkout_Order_Action']);
		
}//end clear session

function clearSession()
{
	unset($_SESSION['getItem']);
	unset($_SESSION['item_Name']);
	unset($_SESSION['showItem']);	
	unset($_SESSION['confirm_Resident']);
	unset($_SESSION['confirmed_Resident']);
	unset($_SESSION['$STUDENT_ID']);
	unset($_SESSION['$F_NAME']);
	unset($_SESSION['$L_NAME']);
	unset($_SESSION['$ROOM_NUMBER']);
	unset($_SESSION['$eligible']);
	unset($_SESSION['$admin']);
	unset($_SESSION['remainingCheckouts']);
	unset($_GET['confirm_Resident']);
	unset($_GET['confirm_Item']);	
	unset($_GET['confirm_Item']);
	unset($_GET['Checkout_Order_Action']);	
	unset($_SESSION['displayItemSearch']);
	unset($_SESSION['sid']);
	
	$_SESSION['confirm_Resident'] = false;
	$_SESSION['clearPage'] = true;
	
}//end clear session

?>