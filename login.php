<?php

require_once('includes/db_config.php');
include("includes/auth.class.php");
$log = new authenticate();

if($_REQUEST['action'] == "login")
{
    if($log->login($_REQUEST['email'], $_REQUEST['password']) == true)
    {
        header('Location: main.php');
    }
    
    else
    {
        header('Location: index.php?e=1');
    }
}


?>