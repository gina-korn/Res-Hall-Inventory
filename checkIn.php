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
		<form action="checkIn.php" method="POST">
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
		
		
	// Make the connection:
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
	
	if(isset($_POST['submitted']))
	{
		$resident_id = $_POST['SSID'];
		
		if(isset($_POST['check']))
		{
			$N = count($_POST['CheckBox']);
			$checkGroup = $_POST['CheckBox'];
			$ITEM_IDGroup = $_POST['ITEM_ID'];
			$name = $_POST['ITEM_Name'];
			
			for($i = 0; $i < $N; $i++)
			{
				//echo $checkGroup[$i]."<br />";
				//echo $ITEM_IDGroup[$i]."<br />";
				
				$cg = (int)$checkGroup[$i];
				$id = (int)$ITEM_IDGroup[$i];
				
				$newQ = "CALL check_in($cg, $id, 0, @stat);";
				$result =  @mysqli_query($dbc, $newQ);
				
				if(!$result)
				{
					echo '<p class = "error">There was a error processing ' . $name[$i] . ' check-in. Please try again</p>';
					die(mysqli_error($dbc));
				} else 
				{
					echo "<p><b>Checked in item: " . $name[$i] . '</b></p>';
				}
			}
		}
		
		if(!empty($_POST['SSID']))
		{			
			// Make the query:
			$q = "Select ORDER_NUMBER, ITEM_ID, ITEM.NAME, DUE_DATE From (CHECKOUT JOIN LINEITEM USING(ORDER_NUMBER)) JOIN ITEM USING(ITEM_ID) WHERE STUDENT_ID = $resident_id AND DATE_CHECKED_IN IS NULL";		
			$r = @mysqli_query ($dbc, $q); // Run the query.
			
			$numRows = mysqli_num_rows($r);
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
							while($row = mysqli_fetch_row($r))
							{
								$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
								echo "<tr bgcolor='$bg'>
									<td>
										<input type='checkbox' name='CheckBox[]' value='$row[0]' />
									</td>
									<td>".$row[2]."</td>
									<td> ".$row[3]."</td>
								</tr>";
								echo "<input type='hidden' name='ITEM_ID[]' value='$row[1]' />";
								echo "<input type='hidden' name='ITEM_Name[]' value='$row[2]' />";
							}
							
						echo"</table>";
				
						echo "<input type='submit' name='check' value='Check In' />
							<input type='hidden' name='submitted' value='1' />
					</fieldset>
				</form>
				<br />";
			} else 
			{
				echo '<h2>This user currently has no items checked out</h2>';
			}
				
			mysqli_close($dbc);
			mysqli_free_result($r);
		}
	}//end name if
	
//Page content ends here //////////////////////////	
	
include ('./includes/footer.html');
?>