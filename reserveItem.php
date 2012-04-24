<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}
	
	$page_title = 'MHIS - Reserve-Item';
	include ('./includes/header.html');
	
	echo '<h1>Item Reservations</h1>';
?>	

<?php
						///---------------------Checking for errors------------------------------------
	$errorArray = array(); // error array
	if(isset($_GET['submitting'])){
		if (empty($_GET['resident_id'])) //checking for empty name
			{
				$errorArray[] = 'Student ID was not entered.';
			}
		else
			{
				// Make the connection:
				$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
				$resident_id = $_GET['resident_id'];
				$q = "SELECT * FROM USER WHERE STUDENT_ID = $resident_id";		
				$r = @mysqli_query ($dbc, $q); // Run the query.
			
				$num = mysqli_num_rows($r);
				
				if (!($num > 0)) { // If it didn't run OK, errpr
					$errorArray[] = 'Student ID was not found.';
					}	
				mysqli_free_result ($r); // Free up the resources.	
				mysqli_close($dbc); // Close the database connection.
			}
		if (empty($_GET['item_name'])) //checking for empty item
			{
				$errorArray[] = 'Please enter an item.';
			}
		else
			{
				// Make the connection:
				$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
				$item_name = $_GET['item_name'];
				$q = "SELECT ITEM_ID FROM ITEM WHERE NAME = " . "'". "$item_name" ."'";		
				$r = @mysqli_query ($dbc, $q); // Run the query.
			
				$num = mysqli_num_rows($r);
				
				if ($num > 0) { // If it didn't run OK, errpr
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					$itemID = $row['ITEM_ID'];
					//echo $_GET['item_name'];
					$_GET['item_name'] = $itemID;
					//echo 'Item ID: ' . $itemID . '</br>';
					//echo $_GET['item_name'];
					}
					}
				else
				{
					$errorArray[] = 'Item was not found ID was not found.';
				}
		
				mysqli_free_result ($r); // Free up the resources.	
				mysqli_close($dbc); // Close the database connection.
			}
		
		if (empty($_GET['start_date'])) //checking for empty start date
			{
				$errorArray[] = 'Please enter a Start Date.';
			}
		else
			{
				$date = $_GET['start_date'];
				$_GET['start_date'] = date("Y-m-d H:i:s",strtotime($date));
				
				if($_GET['start_date'] < date("Y-m-d H:i:s",strtotime(time())))//check the start date does not happen before now
				{
					$errorArray[] = 'Start Date must not have happened in the past.';
				}
				
			}
		if (empty($_GET['end_date'])) //checking for empty end date
			{
				$errorArray[] = 'Please enter an End Date.';
			}
		else
			{
				$date = $_GET['end_date'];
				$_GET['end_date'] = date("Y-m-d H:i:s",strtotime($date));
				
				if (!empty($_GET['start_date'])) //checking for empty start date
				{
					if($_GET['end_date'] < $_GET['start_date']){
						$errorArray[] = 'End Date must occur after the start date.';
					}
				}
			}
			
	
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
		else
		{
			//echo'look I am trying to reserve an item';
			// Make the connection:
			$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
			
			//get the variables from post
			$studentID = $_GET['resident_id'];
			$itemID = $_GET['item_name'];
			$startDate = $_GET['start_date'];
			$endDate = $_GET['end_date'];
			
			$orderNum = 0;
		
			$q = "SELECT MAX(ORDER_NUMBER) + 1  AS 'ORDER_NUM' FROM CHECKOUT";
			$r =  @mysqli_query($dbc, $q);
						
			$num = mysqli_num_rows($r);
						
			if ($num > 0) { // If it ran OK, display the records.
					while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
					$orderNum = $row['ORDER_NUM']	;	
					}
				}	
			//$itemID = 1020043;	
			echo 'OrderNum: ' .$orderNum . ' '. 'Student ID: '. $studentID . ' ' . 'Item ID: '. $itemID . ' ' . 'Start Date: '. $startDate . ' ' . 'End Date: ' . $endDate;				
			//make the query
			$q = "call reserve($orderNum,$studentID, $itemID," . "'" . " $startDate" . "'" . "," . "'" . "$endDate" . "'" . ",@stat);";
			$r =  @mysqli_query($dbc, $q);
						
			$fail = $r;
			if(!$r)
				{
					echo 'Reserve Failed';
				}
			else
				{
					echo 'Reserve Successfull';
				}
			//mysqli_free_result ($r); // Free up the resources.	
		//	mysqli_close($dbc); // Close the database connection.	
		}
	}
?>

<form action="reserveItem.php" method="GET">
	<fieldset class = "forms">
		<?php		
			echo '<label for="name">Student ID: </label><input type="text" name="resident_id" id="resident_id" value="';
				   if(isset($_GET['resident_id'])) echo $_GET['resident_id']; echo'" />';
			
			echo'</br>';
			echo '</br>';
			echo'<label for="item">Item Name: </label> <input type="text" name="item_name" id="item_name" value="';
								if(isset($_GET['item_name'])) echo $_GET['item_name']; echo'" />';	   
			echo '</br>';
			echo '</br>';
	
			echo'
			<script>
			$(function() {
			$( "#start_date" ).datepicker();
			$( "#end_date" ).datepicker();
			});
			</script>
			
				Start Date: <input type="text" name="start_date" id="start_date" ';
				if(isset($_GET['start_date'])) echo $_GET['start_date']; echo'" />';	
				
				echo'
				End Date: <input type="text" name="end_date" id="end_date" ';
				if(isset($_GET['end_date'])) echo $_GET['end_date']; echo'" />';
				
				echo'
				<input type="submit" name="createRes" value="Create Reservation" />
				<input type="hidden" name="submitting" value="1" />
			';				
		?>
	</fieldset>	
</form>



<?php
include ('./includes/footer.html');
?>