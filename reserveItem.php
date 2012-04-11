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
	
	
?>	

<form action="reserveItem.php" method="post">
	<fieldset class = "forms">
		<?php		
			
			//get the starting time and date
			//get class into the page
			require_once('includes/tc_calendar.php');
			$date1_default = "2012-04-09";
			$date2_default = "2012-04-15";
			
			
			$myCalendar = new tc_calendar("date1", true);
			$myCalendar->setIcon("images/iconCalendar.gif");
			$myCalendar->setDate(date('d', strtotime($date1_default))
				, date('m', strtotime($date1_default))
				, date('Y', strtotime($date1_default)));
			// $myCalendar->setPath("./");
			$myCalendar->setYearInterval(1970, 2020);
			$myCalendar->setAlignment('left', 'bottom');
			$myCalendar->setDatePair('date1', 'date2', $date2_default);
			$myCalendar->writeScript();	  
			
			echo'</br>';
			echo'</br>';
			//get the ending time and date
			$myCalendar = new tc_calendar("date2", true);
			$myCalendar->setIcon("images/iconCalendar.gif");
			$myCalendar->setDate(date('d', strtotime($date2_default))
				, date('m', strtotime($date2_default))
				, date('Y', strtotime($date2_default)));
			// $myCalendar->setPath("/");
			$myCalendar->setYearInterval(1970, 2020);
			$myCalendar->setAlignment('left', 'bottom');
			$myCalendar->setDatePair('date1', 'date2', $date3_default);
			$myCalendar->writeScript();	  
			
			echo'</br>';
			echo'</br>';
			echo'</br>';
			echo '<label for="name">Student ID </label><input type="text" name="resident_id" id="resident_id" value="';
				   if(isset($_GET['name'])) echo $_GET['name']; echo'" />';
			
			echo'</br>';			
			echo'<label for="item">Item Name: </label> <input type="text" name="item" id="item_name" value="';
								if(isset($_GET['item'])) echo $_GET['item']; echo'" />';	   
				
		?>
	</fieldset>	
</form>

<?php
include ('./includes/footer.html');
?>