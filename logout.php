<?php

include("includes/auth.class.php");
$log = new authenticate();
session_destroy();
header("Location: index.php");

?>