<?php
	
	include('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}
	
	$page_title = 'MHIS - Item Check-In';
	include ('./includes/header.html');
	
	
	echo '<h1>Item Check-In</h1>';

		// Initial 'find student' form //////	
		echo '
		<form action="checkIn.php" method="POST" name = "getResident">
			<fieldset class="forms">
				<legend><h2>Find user</h2></legend>
				<label for="SSID">Student ID</label> <input type="text" name="SSID" id="SSID" value="'; 
				if(isset($_POST['SSID'])) echo $_POST['SSID']; 
				echo '" />
				<br /><br />
				<input type="submit" name="submit" value="Submit Student ID" />
				<input type="hidden" name="submitted" value="1" />
			</fieldset>
		</form></br />';
		
		//sets the auto focus to the form above it. fill in form name and text box name
				echo '<script type="text/javascript" language="JavaScript">
							document.forms["getResident"].elements["name"].focus();
						</script>';
		
	// Make the connection:
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
	
	//Check in all
	if(isset($_POST['checkAll']))
	{
		$resident_id = $_POST['checkAll'];
		
		//NO SQL IN THE PHP
		$que = "Select ORDER_NUMBER, ITEM_ID, ITEM.NAME, DUE_DATE From (CHECKOUT JOIN LINEITEM USING(ORDER_NUMBER)) JOIN ITEM USING(ITEM_ID) WHERE STUDENT_ID = $resident_id AND DATE_CHECKED_IN IS NULL AND CHECKOUT_TYPE = 'checkout'";		
		$res = @mysqli_query ($dbc, $que); // Run the query.
		
		if(!$res)
			echo "No res";
		
		while($row = mysqli_fetch_row($res))
		{
			$order_id = $row[0];
			$item_id = $row[1];
			$newQ = "CALL check_in($order_id, $item_id, 0, @stat);"; //Order ID, Item ID, damage, status
			$result =  @mysqli_query($dbc, $newQ);
		}
		
		mysqli_close($dbc);
		@mysqli_free_result($res);
	}
	
	//Item check in
	if(isset($_POST['checkIn']))
	{
		$myArray = explode("~", $_POST['checkIn']);
		
		$cg = $myArray[0];
		$ITEM_ID = $myArray[1];
		$ITEM_NAME = $myArray[2];
		
		$newQ = "CALL check_in($cg, $ITEM_ID, 0, @stat);"; //Order ID, Item ID, damage, status
		$result =  @mysqli_query($dbc, $newQ);
		
		if(!$result)
		{
			echo '<p class = "error">There was a error processing ' . $name[$i] . ' check-in. Please try again</p>';
			die(mysqli_error($dbc));
		} else 
		{
			echo "<p><b>Checked in item: " . $ITEM_NAME . '</b></p>';
		}
	}
	
	
	if(isset($_POST['submitted']))
	{
		$resident_id = $_POST['SSID'];
		
		
		
		if(!empty($_POST['SSID']))
		{			
			// Make the query:
			
			//NO SQL IN THE PHP
			$q = "Select ORDER_NUMBER, ITEM_ID, ITEM.NAME, DUE_DATE From (CHECKOUT JOIN LINEITEM USING(ORDER_NUMBER)) JOIN ITEM USING(ITEM_ID) WHERE STUDENT_ID = $resident_id AND DATE_CHECKED_IN IS NULL AND UPPER(CHECKOUT_TYPE) = 'CHECKOUT'";		
			$r = @mysqli_query ($dbc, $q); // Run the query.
			
			if(!$r)
			{
				echo "<p class='error'>No user with that Student ID</p>";
				die();
			}
			else
			{
				$numRows = @mysqli_num_rows($r);
			}
			
			if ($numRows > 0) 
			{			
			
				
				echo "<form action='checkIn.php' method='POST'>
					<fieldset class='forms'>
						<legend><h2>Checked out items</h2></legend>
						<table width='100%' cellpadding='2' cellspacing='0'>
							<tr>
								<td></td>
								<td><b>Item Name</b></td>
								<td><b>Due Date</b></td>
							</tr>";
							$bg = '#ebe3c3';
							
							echo '<button class="buttons" name="checkAll"	value="'. $resident_id. '">
											<img src="./images/edit.png" length="20" width="20" /></button>';
											
							while($row = mysqli_fetch_row($r))
							{
								//$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
								$array[0] = $row[0];
								$array[1] = $row[1];
								$array[2] = $row[2];
								
								$myData = implode("~", $array);
								
								echo '<tr>
									<td>
										<button class="buttons" name="checkIn"	value="' . $myData .'">
											<img src="./images/edit.png" length="20" width="20" /></button>
									</td>
									<td>' .$row[2]. ' </td>
									<td>' .$row[3]. ' </td>
								</tr>';
								unset($array);
							}
							
						echo"</table>";
				
						//echo "<input type='submit' name='check' value='Check In' />
							//<input type='hidden' name='submitted' value='1' />
					echo"</fieldset>
				</form>
				<br />";
			} else 
			{
				echo '<h2>This user currently has no items checked out</h2>';
			}
				
			mysqli_close($dbc);
			
			@mysqli_free_result($r);
		}
	}//end name if
	
//Page content ends here //////////////////////////	
	
include ('./includes/footer.html');
?>