<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - Manage Users';
	include ('./includes/header.html');
	
?>

<style>
		
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>

<h1>Manage Users</h1>


	<script>
	$(function() {
		
		
		var admin = $( "#admin" ),
			studID = $( "#studID" ),
			fName = $( "#fName" ),
			lName = $( "#lName" ),
			email = $( "#email" ),
			hall = $( "#hall" ),
			room = $( "#room" )
			
			allFields = $( [] ).add( admin ).add( studID ).add( fName ).add( lName).add( email ).add( hall ).add( room ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
				
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			modal: true,
			buttons: {
				"Create an account": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkRegexp( studID, /^[0-9]+$/i, "Student ID must be numbers only" );
					bValid = bValid && checkLength( fName, "first name", 2, 16 );
					bValid = bValid && checkLength( lName, "last name", 2, 16 );
					bValid = bValid && checkLength( email, "email", 6, 80 );

					bValid = bValid && checkRegexp( fName, /^[a-z]([0-9a-z_])+$/i, "First name may consist of a-z, 0-9, underscores, begin with a letter." );
					bValid = bValid && checkRegexp( lName, /^[a-z]([0-9a-z_])+$/i, "Last name may consist of a-z, 0-9, underscores, begin with a letter." );
					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
					bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
					bValid = bValid && checkRegexp( room, /^[0-9]+$/i, "Room # must only contain numbers" );

					if ( bValid ) {
						$( "#addForm" ).submit();
					}
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

		$( "#create-user" )
			.button()
			.click(function() {
				$( "#dialog-form" ).dialog( "open" );
			});
	});
	</script>




<div id="dialog-form" title="Create New User">
	<p class="validateTips">All form fields are required.</p>

	<form id="addForm" action="newUser.php" method="post">
	<fieldset>
    	<table>
        	<tr>
            	<td><label for="admin">Admin</label></td>
                <td>
                	<select name="admin" id="admin" class="select ui-widget-content ui-corner-all">
                    	<option value="0">No</option>
                        <option value="1">Yes</option>
                   	</select>
                </td>
            </tr>
        	<tr>
            	<td><label for="studID">Student ID</label></td>
                <td><input type="text" name="studID" id="studID" class="text ui-widget-content ui-corner-all" /></td>
            </tr>
            <tr>
            	<td><label for="fName">First Name</label></td>
                <td><input type="text" name="fName" id="fName" class="text ui-widget-content ui-corner-all" /></td>
            </tr>
            <tr>
            	<td><label for="lName">Last Name</label></td>
                <td><input type="text" name="lName" id="lName" class="text ui-widget-content ui-corner-all" /></td>
            </tr>
            <tr>
            	<td><label for="email">Email</label></td>
                <td><input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" /></td>
            </tr>
            <tr>
            	<td><label for="hall">Hall</label></td>
                <td>
                	<select name="hall" id="hall" class="select ui-widget-content ui-corner-all">
                    	<option value="1">Morrison</option>
                   	</select>
                </td>
            </tr>
            <tr>
            	<td><label for="room">Room #</label></td>
                <td><input type="text" name="room" id="room" value="" style="width: 30px;" class="text ui-widget-content ui-corner-all" /></td>
            </tr>
        </table>
		
	</fieldset>
	</form>
</div>

<button id="create-user"><img src="images/add.png" /> Create New User</button>

<?php

	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
	
	$query = 'SELECT * FROM USER JOIN RESIDENCE_HALL ON RESIDENCE_HALL.HALL_ID = USER.HALL_ID ORDER BY L_NAME';	 					

	$result = @mysqli_query($dbc, $query); 
	
	$numRows = @mysqli_num_rows($result);
	
	if ($numRows > 0) { 
	
		echo "<h2>There are currently $numRows active users</h2>";
	
		// Table header:
		echo '<table><tr><td width="20">&nbsp;</td><td width="124"><h3>Last Name</h3></td>
			<td width="140"><h3>First Name</h3></td>
			<td width="150"><h3>Email</h3></td>
			<td width="95"><h3>Hall</h3></td>
			<td width="50"><h3>Room</h3></td></tr></table>
			<div class="scrollBox"><table width="100%" cellspacing="0" cellpadding="2">';
		
		$bg = '#ebe3c3';
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
			$bg = ($bg=='#f7f0d3' ? '#ebe3c3' : '#f7f0d3');
			echo '<tr bgcolor="' . $bg . '">';
			
			if ($row['admin'] == 1)
				echo '<td width="16"><img src="images/star.png" /></td>';
			else
				echo '<td width="16">&nbsp;</td>';
			
			echo '<td width="150">' . $row['L_NAME'] . '</td><td width="150">' . 
			$row['F_NAME'] . '</td><td width="150"><a href="mailto:' . $row['EMAIL'] . '" />' . $row['EMAIL'] . '</a></td>' . 
			'<td width="100">' . $row['NAME'] . '</td><td width="50">' . $row['ROOM_NUMBER'] . '</td><td><a onclick="return confirm(\'Are you sure you want to delete this user?\');" href="deleteUser.php?studID=' . $row['STUDENT_ID'] . '" />' . 
			'<img src="images/delete.png" /></a></td></tr>';
		}
	
		echo '</table></div>'; // Close the table & div
		
		mysqli_free_result ($result);
	
	} else { // If no items were returned
		echo '<p class="error">There are currently no items.</p>';
	}
	
	//Close the db connection
	mysqli_close($dbc);

?>


<?
//footer:
include ('./includes/footer.html');

if ($_GET['m'])
		echo '<script>$(document).ready(function(){var t=setTimeout("alert(\'The user account has been created and an email has been sent with login credentials.\')", 750);});</script>';

if ($_GET['d'])
		echo '<script>$(document).ready(function(){var t=setTimeout("alert(\'The user account has been deleted.\')", 750);});</script>';

?>