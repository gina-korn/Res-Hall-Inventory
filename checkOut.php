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
	include ('./includes/header.html');
	
	echo '<h1>Item Check-Out</h1>';
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
								if(isset($_GET['confirm_Item']) || isset($_SESSION['item_Name']))
								{
									$item_Name = $_SESSION['item_Name'];
									echo $item_Name;
								}
								echo'
							</td>
						</tr>
						<tr align="right">
							<td>
								<input type="submit" name="Checkout_Order_Action" value="Clear Order" />
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
				</Form>
			</fieldset> <br />
			';
											//-------- Handeling Resident Information--------------
			if($_SESSION['confirm_Resident']  == false)
			{	
				//this is the form to grab the resident's information based off of their student ID		
				echo'
					<form action="checkOut.php" method="GET" name = "getResident">
						<fieldset class = "forms">
							<label for="name">Student ID </label><br /> <input type="text" name="resident_id" id="resident_id" value="';
								if(isset($_GET['name'])) echo $_GET['name']; echo'" />
							<input type="submit" name="submit" value="Go" />
							<input type="hidden" name="submitting_resident_name" value="1" />	
						</fieldset>
					</form>
					';
				
				//sets the auto focus to the form above it. fill in form name and text box name
				echo '<script type="text/javascript" language="JavaScript">
							document.forms["getResident"].elements["name"].focus();
						</script>';	
			}	
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
			// Make the connection:
			$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
			
			// Make the query:
			$q = "SELECT * FROM USER WHERE STUDENT_ID = $resident_id";		
			$r = @mysqli_query ($dbc, $q); // Run the query.
			
			$num = mysqli_num_rows($r);
				
			if ($num > 0) { // If it ran OK, display the records.
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						$student_id = $resident_id;;
						$F_NAME = $row['F_NAME'];
						$L_NAME = $row['L_NAME'];
						$ROOM_NUMBER =  $row['ROOM_NUMBER'] ;
						$eligible = $row['AVAILABLE_CO'] ;
						$admin = $row['admin'] ;
						
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
											<label for="name"><b>Resident: </b></label>								
										</td>
										<td>
											';echo $F_NAME . " " . $L_NAME ; echo'
										</td>
										<td>
											';
											if($eligible > 0)
											{
											echo'
												<input type="submit" name="confirmed_Resident" value="Go" />
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
					<br />
				';						
			}
			else { // If no records were returned.
					echo '<p class="error">No user exists with that ID</p>';
				}
		}
								//---------display the form to get the first Item------------------
										
			if((isset($_GET['confirm_Resident']) || $_SESSION['confirmed_Resident'] == true) && !(isset($_GET['Checkout_Order_Action'])))
			{
				$_SESSION['confirmed_Resident'] = true;
				//Grabs item based off of item name
				echo'
					<form action="checkOut.php" method="GET" name = "getItemName">
						<fieldset class="forms">
							<label for="item">Item Name: </label> <input type="text" name="item" id="item_name" value="';
								if(isset($_GET['item'])) echo $_GET['item']; echo'" />
							<input type="submit" name="submit" value="getItem" />
							<input type="hidden" name="getItem" value="1" />
						</fieldset>
					</form>			
				';
				
				//sets the auto focus to the form above it. fill in form name and text box name
				echo '<script type="text/javascript" language="JavaScript">
						document.forms["getItemName"].elements["item"].focus();
					  </script>';	
			}	
							
							// ---------Making Database call for the first item---------------
							
		if((isset($_GET['getItem']) || $_SESSION['getItem'] == true) && !(isset($_GET['confirm_Item'])) && !(isset($_GET['Checkout_Order_Action'])) )
		{
			$_SESSION['getItem'] = true;
			
			//get the item name from the form getItemName
			$itemName = $_GET['item'];
			
			// Make the connection:
			$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
			
			// Make the query:
			$q = "SELECT ITEM_ID, NAME, QUANTITY, AVAILABLE, CATEGORY_ID FROM ITEM WHERE NAME = " . "'". "$itemName" ."'";		
			$r = @mysqli_query ($dbc, $q); // Run the query.
			
			$num = mysqli_num_rows($r);
					
			if ($num > 0) { // If it ran OK, display the records.
				
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
						$ITEM_ID = $row['ITEM_ID'];
						$NAME = $row['NAME'];
						$QUANTITY = $row['QUANTITY'];
						$AVAILABLE =  $row['AVAILABLE'] ;
						$CATEGORY =  $row['CATEGORY'] ;
					
										
					} // End of WHILE loop.
				
				$_SESSION['item_Name'] = $NAME;	
				$_SESSION['$ITEM_ID'] = $ITEM_ID;
				
				mysqli_free_result ($r); // Free up the resources.	
				mysqli_close($dbc); // Close the database connection.
				
				
								//-------------- Have office worker confirm, right item--------------------
				
				echo '
					<br />
						<form action="checkOut.php" method="GET">
							<fieldset class="forms" >				
								<table>
									<tr>
										<td>  
											<label for="name"><b>Item: </b></label>								
										</td>
										<td>
										';// item name
										echo'
											';echo $NAME; echo'
										</td>
										<td>  
											<label for="name"><b>Available for Check Out: </b></label>								
										</td>
										<td>
										';// item availability
										echo'
											';echo $AVAILABLE; echo'
										</td>
										<td>
											';// item availability
											if($AVAILABLE > 0)
											{
											echo'
												<input type="submit" name="confirmed_Item" value="Go" />
												<input type="hidden" name="confirm_Item" value="1" />
												';
											}
											else
											{
												echo '<p class="error"> ' . $NAME . 'is not currently available for Check Out.</p>';
											}
											echo '
										</td>
									</tr>
								</table>	
							</fieldset>
						</form>
					<br />
				';								
			}
			else { // If no records were returned.
				echo '<p class="error">* No item exists with that name</p>';
				
			}	
			
		}

												//-------------Checking Out Item---------------------
		if(isset($_GET['Checkout_Order_Action']))
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
						//echo$TrueDueDate;
						$TrueDueDate = '20' . $TrueDueDate;
						//echo ' day ';
					//	echo $trueDate;
						//echo 'Due Date ';
					//	echo $dueDate;
						$studentID = $_SESSION['$STUDENT_ID'];
						$OW_ID = $_SESSION['OW_ID'];
						$item_ID = $ITEM_ID = $_SESSION['$ITEM_ID'];
						
						$admin = $_SESSION['$admin'];
						$orderNum = 0;
						//$num = mysqli_num_rows($r);
				
						
						$q = "SELECT MAX(ORDER_NUMBER) + 1  AS 'ORDER_NUM' FROM CHECKOUT";
						$r =  @mysqli_query($dbc, $q);
						
						$num = mysqli_num_rows($r);
						
						if ($num > 0) { // If it ran OK, display the records.
							while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
								$orderNum = $row['ORDER_NUM']	;	
						}
						}
						echo $orderNum + 'Hope this works';
						mysqli_free_result ($r); // Free up the resources.	
						$type = 'checkout';
						
						
						echo $orderNum . " " . $studentID . " " . $item_ID . " " . $type . " " . $trueDate . " Due Date: " .  $TrueDueDate; 
						$q = "call check_out($orderNum,$studentID, $item_ID, 'checkout' ,now(),now() + interval 1 day,@stat);";
						$r =  @mysqli_query($dbc, $q);
						
						$fail = $r;
						if(!$r)
						{
							echo 'Check out Failed';
						}
						else
						{
							echo 'Check out Successfull';
						}
						
						//mysqli_free_result ($r); // Free up the resources.	
						mysqli_close($dbc); // Close the database connection.
						
				}
				else
				{
					/*$_GET['confirm_Resident'] = null;
					$_GET['Checkout_Order_Action'] = null;
					$_GET['confirm_Item'] = null;
					$_GET['getItem'] = null;
					$_SESSION['getItem'] = null;
					$_SESSION['item_Name'] = null;
					$_SESSION['confirm_Resident'] = false;
					*/
					
					unset($_GET['confirm_Resident']);
					unset($_GET['confirm_Item']);	
					unset($_GET['confirm_Item']);		
					unset($_SESSION['getItem']);
					unset($_SESSION['item_Name']);	
					unset($_SESSION['confirm_Resident']);
					
					if(isset($_GET['confirm_Resident']))
					{
						echo 'this did not get cleared - confirm resident GET';
						echo $_GET['confirm_Resident'];
					}
					if(isset($_SESSION['confirm_Resident']))
					{
						echo 'this did not get cleared - Sesssion resident ';
						echo $_SESSION['confirm_Resident'];
					}
					
					$_SESSION['confirm_Resident'] = false;
				}
				//echo $nameSet;
			}					
?>

<?php
include ('./includes/footer.html');
?>