<?php



?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <link rel='stylesheet' type='text/css' href='css/bootstrap.css'/>
        <link rel='stylesheet' type='text/css' href='css/bootstrap-datetimepicker.min.css'/>
        <link rel='stylesheet' type='text/css' href='css/custom.css'/>

    </head>
    <body>	

    <div class="container">

    	<div class="col-md-12">
    		<p class="text-center" style="margin-top:5%; font-weight:bold; font-size:1.5em;">This page shows job status count between selected dates.</p>
    	</div>
    	
    	<div class="well">
	    	<form action="getdata.php" method="POST" role="form">

	    		<!-- 
	    		<div class="form-group">
	    			<label for="resourceId"> Enter resource ids</label>
	    			<textarea id="resourceId" name="resource_ids" class="form-contol col-md-12" maxlength="3000" placeholder="Comma Separated Resource ids"></textarea>
	    			<br/>
	    			If not entered, default resource id is taken as -<br/>
	    			stampede.tacc.xsede.org_af57850b-103b-49a1-aab2-27cb070d3bd9
	    		</div>
	    		-->

	    		<?php 
	    		$sDate = "";
	    		if( isset( $_GET["start_date"]))
	    		{
	    			$sDate = $_GET["start_date"];
	    		}

	    		$eDate = "";
	    		if( isset( $_GET["end_date"]))
	    		{
	    			$eDate = $_GET["end_date"];
	    		}

	    		?>
	    		<div class="form-group">
	    			<label for="sdate"> Please enter Start Date in MM/DD/YY format</label>
	    			<div id="datepicker1" class="input-append">
	    				<input data-format="mm-dd-yy" type="text" required="required" id="sdate" name="start_date" class="form-contol" placeholder="Start Date" value="<?php echo $eDate; ?>"/>
    				</div>
	    		</div>

	    		<div class="form-group">
	    			<label for="edate"> Please enter End Date in MM/DD/YY format</label>
	    			<div id="datepicker2" class="input-append">
	    				<input data-format="mm-dd-yy" type="text" required="required" id="edate" name="end_date" class="form-contol" placeholder="End Date" value="<?php echo $eDate; ?>">
    				</div>
	    		</div>

	    		<div class="form-group">
	    			<button type="submit" class="btn btn-default">Submit</button>
	    		</div>

	    	</form>
	    </div>
	    <div class="col-md-5" style="text-align:center; padding: 5%; font-weight:bold;">
	    	Start Date : <?php echo $_GET["start_date"]; ?>
	    </div>
	    <div class="col-md-5" style="text-align:center; padding: 5%; font-weight:bold;">
	    	End Date : <?php echo $_GET["end_date"]; ?>
	    </div>
	    <table class="table table-striped">
	    	<tr>
	    		<th>Resource Id</th>
	    		<th>Failed Count</th>
	    		<th>Cancelled count</th>
	    		<th>Completed Count</th>
	    		<th>Total Created</th>
	    		<th>Success percent</th>
	    	</tr>

	    	<?php
	    	if( isset( $_GET["start_date"]))
	    	{
	    		$fJobs = explode(",", $_GET["fJobs"]);
	    		$cJobs = explode(",", $_GET["cJobs"]);
	    		$tJobs = explode(",", $_GET["tJobs"]);
	    		$canJobs = explode(",", $_GET["canJobs"]);
	    		$resourceIds = explode(",", $_GET["resource_ids"]);
	    	?>
		    	<?php
		    	$i=0;
		    	foreach( $resourceIds as $resource)
		    	{

	    		?>
			    	<tr>
			    		<td><?php echo $resource; ?></td>
			    		<td><?php echo $fJobs[$i]; ?></td>
			    		<td><?php echo $canJobs[$i]; ?></td>
			    		<td><?php echo $cJobs[$i]; ?></td>
		    			<td><?php echo $tJobs[$i]; ?></td>
		    			<td><?php echo number_format( (float)( ( ( $canJobs[$i] + $cJobs[$i] ) /$tJobs[$i] )*100 ), 2, '.', '' ); ?>%</td>
		    		</tr>
		    	<?php
		    		$i++;
		    	}
	    	}
	    	?>
		</table>
	</div>
    		

    <script type='text/javascript' src='js/jquery.js'></script>
    <script type='text/javascript' src='js/bootstrap.js'></script>
    <script type='text/javascript' src='js/moment.js'></script>
    <script type='text/javascript' src='js/bootstrap-datetimepicker.min.js'></script>
    <script type='text/javascript' src='js/script.js'></script>
    <script type='text/javascript' >

    $(document).ready( function(){ 
    	$('#datepicker1').datetimepicker({
    		pickTime : false
    	});

	    $('#datepicker2').datetimepicker({
	      pickTime: false
	    });

    });
    </script>
	</body>
</html>
