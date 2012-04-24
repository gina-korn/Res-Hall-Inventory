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

<script>
	
	
	function validate()
	{
		if (document.getElementById('new_password').value != document.getElementById('retype_password').value)
			{
				alert('Your passwords do not match.');
				return false;
			}
		if (document.getElementById('old_password').value == "" || document.getElementById('new_password').value == "" ||
			document.getElementById('retype_password').value == "")
			{
				alert('You must fill in each password text box.');
				return false;
			}
		if (document.getElementById('new_password').value.length < 6)
		{
			alert('Your password must be at least six characters long.');
			return false;
		}
		return true;
	}//end validate

</script>


<h1>Change Password</h1>



<form id="form1" action="updatePassword.php" method="post">

	<table>
    	<tr>
        	<td>Old Password:</td><td><input type="password" name="old_password" id="old_password" /></td>
        </tr>
        <tr>
        	<td>New Password:</td><td><input type="password" name="new_password" id="new_password" /></td>
        </tr>    
        <tr>
        	<td>Retype Password:</td><td><input type="password" name="retype_password" id="retype_password" /></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td colspan="2"><button type="submit" title="Change Password" value="Change Password" onclick="return validate();">Change Password</button></td>
        </tr>
    </table>    
    

</form>


<?
//footer:
include ('./includes/footer.html');

if ($_GET['m'])
		echo '<script>$(document).ready(function(){var t=setTimeout("alert(\'The user account has been created and an email has been sent with login credentials.\')", 750);});</script>';

if ($_GET['d'])
		echo '<script>$(document).ready(function(){var t=setTimeout("alert(\'The user account has been deleted.\')", 750);});</script>';
		
if ($_GET['p'])
echo '<script>$(document).ready(function(){var t=setTimeout("alert(\'The users password has been reset.\')", 750);});</script>';

?>