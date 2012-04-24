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
	///////////
	echo '<h1>Item Reservations</h1>';
?>	

<form action="reserveItem.php" method="post">
	<fieldset class = "forms">
		<?php		
			echo '<label for="name">Student ID: </label><input type="text" name="resident_id" id="resident_id" value="';
				   if(isset($_GET['name'])) echo $_GET['name']; echo'" />';
			
			echo'</br>';
			echo '</br>';
			echo'<label for="item">Item Name: </label> <input type="text" name="item" id="item_name" value="';
								if(isset($_GET['item'])) echo $_GET['item']; echo'" />';	   
			echo '</br>';
			echo '</br>';
			
			echo'
			<script>
			$(function() {
			$( "#Start_date" ).datepicker();
			$( "#End_Date" ).datepicker();
			});
			</script>
			
				Start Date: <input id="Start_date" type="text"> End Date: <input id="End_Date" type="text">
			';
		
					
				
			

				
		?>
	</fieldset>	
</form>

<?php
include ('./includes/footer.html');
?>