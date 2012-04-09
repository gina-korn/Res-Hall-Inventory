<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Inventory Management Login</title>
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/print.css" type="text/css" media="print" />
    </head>
    
    <body>
    	<div id="login">
        <h1>Inventory Management Login</h1>
        	
        	<?php
        	require_once('includes/db_config.php');
        	include("includes/auth.class.php");
			$log = new authenticate();
			$log->loginForm("loginForm", 'loginFormStyle', "login.php");
			
        	?>

    	</div>
    </body>
    	
</html>