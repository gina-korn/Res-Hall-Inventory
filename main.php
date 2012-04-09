<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'Morrison Hall Inventory System';

	include ('./includes/header.html');
	
?>

<!-- Insert content here -->

    
   	<h1>Welcome to the Site!</h1>
    
    <p>
    	<img src="images/rh_small_1.jpg" class="float">
    	<h3>You have successfully logged in. Please make a selection from the menu above.</h3>
    </p>
	<p>If you have admin rights, you should see a list of reports to the left <del>(They're all empty links for now)</del>
    	and if you don't, you shouldn't see anything. The sidebar is in header.html if you need to edit it.</p>
    <p>
    	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean iaculis pellentesque mauris vel 
        eleifend. Vivamus ultricies mi sit amet ipsum congue elementum. Sed id urna vel lorem volutpat 
        bibendum. Fusce et libero vel lacus adipiscing accumsan. Aenean molestie sem sit amet ipsum blandit 
        id viverra mauris imperdiet. Nulla scelerisque nibh in diam molestie in facilisis eros varius. 
        Sed blandit, dolor eu lacinia malesuada, quam odio porta tellus, quis varius nibh quam id lorem.
        Duis venenatis faucibus metus id pulvinar. Suspendisse potenti. Nulla semper tellus non nulla 
        condimentum cursus. Mauris posuere tempus dolor id dignissim. Integer mattis sodales velit, id 
        placerat quam egestas id.  
	</p>
    <p>
    	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean iaculis pellentesque mauris vel 
        eleifend. Vivamus ultricies mi sit amet ipsum congue elementum. Sed id urna vel lorem volutpat 
        bibendum. Fusce et libero vel lacus adipiscing accumsan. Aenean molestie sem sit amet ipsum blandit 
        id viverra mauris imperdiet. Nulla scelerisque nibh in diam molestie in facilisis eros varius. 
        Sed blandit, dolor eu lacinia malesuada, quam odio porta tellus, quis varius nibh quam id lorem.
        Duis venenatis faucibus metus id pulvinar. Suspendisse potenti. Nulla semper tellus non nulla 
        condimentum cursus. Mauris posuere tempus dolor id dignissim. Integer mattis sodales velit, id 
        placerat quam egestas id.  
	</p>
<!-- Page content ends here -->                
                
<?php
include ('./includes/footer.html');
?>
