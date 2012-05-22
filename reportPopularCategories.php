<?php
	
	require_once('includes/db_config.php');
	include("includes/auth.class.php");
	$log = new authenticate();
	
	if($log->loginCheck($_SESSION['email'], $_SESSION['password']) == false)
	{
		session_destroy();
    	header('Location: index.php?e=1');
   	}

	$page_title = 'MHIS - Popular Categories';
	include ('./includes/header.html');
	echo "<h1>Popular Categories</h1>";	

	//Connect & Query DB:
	$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
	$query = "SELECT * FROM popular_categories ORDER BY name";
	$result = @mysqli_query ($dbc, $query); 	
	
	// If there is a report found
	if($result)
	{		
		echo '	
		<div class="outer">
		<div class="innera">
		<table id="itemsTable" class="tablesorter" cellspacing="0" celpadding="3"> 
			<thead> 
				<tr align="left">
					<th width="310"><b>Category Name</b></th>
					<th width="310"><b>Times Checked Out</b></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5"></td>
				</tr>
			</tfoot>
			<tbody>';
		  
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
			{
				echo '<tr>
					<td width="310"><b>' . $row['name'] . '</b></td>
					<td width="300">' . $row['times_checked_out'] . '</td>
				</tr>';
			}
		echo '</tbody></table></div></div><br /><br />';
						
	} else
	{
		echo "<h2>There are currently no popular categories</h2>";	
	}
	
	
	//Close the db connection
	mysqli_close($dbc); 
	
?>

<script type="text/javascript" id="js">
$(document).ready(function() 
    { 
        $("#itemsTable").tablesorter({widgets: ['zebra']});
    } 
); 

</script>


<!--  PIE CHART -->


		<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                plotBackgroundColor: '#F7F0D3',
                plotBorderWidth: null,
                plotShadow: false,
				backgroundColor: '#F7F0D3'
            },
            title: {
                text: 'Popular Categories'
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.point.name +'</b>: ' + (Math.round(this.percentage*1)/1) + '%';
                }
            },
			credits: {
				enabled: false
			},
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Popular Categories',
                data: [
                    <?php
					
						$dbc = @mysqli_connect (HOST, USER, PASSWORD, DBNAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );		
						$query = "SELECT * FROM popular_categories ORDER BY name";
						$result = @mysqli_query ($dbc, $query); 
						$numRows = @mysqli_num_rows($result);
						
						while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
						{
							$name = str_replace("'", "\'", $row["name"]);
							
							if ($i < $numRows)
								echo '["' . $name . '", ' . $row["times_checked_out"] . '],';
							else
								echo '["' . $name . '", ' . $row["times_checked_out"] . ']';
						}
						
						mysqli_close($dbc); 
						
					?>
                ]
            }]
        });
    });
    
});
		</script>



<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>
<div id="container" style="width: 400px; height: 400px;"></div>


<?php

//footer:
include ('./includes/footer.html');

?>