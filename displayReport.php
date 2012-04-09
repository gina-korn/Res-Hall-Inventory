<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - View Report';
	include ('./includes/header.html');
	
	if(isset($_GET['report']))
	{
		//Connect & Query DB:
		$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		

		$reportName = mysqli_real_escape_string($dbc, trim($_GET['report']));
		$query = "SELECT * FROM $reportName";
		
		//Make the report name look nicer for display on the site
		$reportName = strtolower (str_replace ("_", " ", $reportName));				
		$result = @mysqli_query ($dbc, $query); 	
		
		//No peeking at user info ;-)
		if($reportName == 'user')
		{
			$result = NULL;
		}
		
		// If there is a report found
		if($result)
		{		
			$numRows = mysqli_num_rows($result);
			if ($numRows > 0) 
			{ 
				$fields = mysqli_fetch_fields($result);
				echo "<h1>Displaying results for $reportName</h1>";	
				echo '<table cellspacing="0" cellpadding="5" class="reports"><tr>';
				foreach($fields as $fi => $f) 
				{
					$z = ucfirst( strtolower ( str_replace ( "_", " ", (string)$f->name )));	
					echo "<td><b>$z</b></td>";
				}
				echo '</tr>';
				
				$bg = '#ebe3c3';
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
				{
					$numCols = count($row);	
					$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
					echo '<tr bgcolor="' . $bg . '">';				
					
					foreach ($row as &$val) {
						 echo "<td>$val</td>";
					}				
					echo '</tr>';
				}
				echo '</table>';
			} else
			{
				$reportName = ucfirst($reportName);
				echo "<h1>$reportName currently empty</h1>";	
			}
						
		} else
		{
			echo '<h1>Display Report</h1>';
			echo "<h2 class='error'>No results found for $reportName</h2>";	
		}
		
		//Close the db connection
		mysqli_close($dbc); 
	} else 
	{
		echo '<h1>Display Report</h1>';
		echo '<h2 class="error">No report currently selected</h2>';	
	}
	
	//footer:
	include ('./includes/footer.html');
	
	
?>