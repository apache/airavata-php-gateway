<?php

$serverType = $_POST["server_type"];
//settings for GF5 -
$host = "gridfarm005.ucs.indiana.edu";
$un = "jobstatus";
$pw = "jobstatus345";
$schema = "ultrascan_airavata";
$port = 5123;
if( $serverType == "gw111")
{
	$host = "gw85.iu.xsede.org";
	$un = "jobstatus";
	$pw = "jobstatus345";
	$schema = "airavata_gta_dev";
	$port = 3306;
}
if( $serverType == "gw127")
{
	$host = "gw85.iu.xsede.org";
	$un = "jobstatus";
	$pw = "jobstatus345";
	$schema = "airavata_gta_prod";
	$port = 3306;
}

// Create connection
$con=mysqli_connect($host,$un,$pw,$schema, $port);

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$startDate = date('Y-m-d ', strtotime($_POST["start_date"] )) . "00:00:00";
$endDate = date('Y-m-d ', strtotime($_POST["end_date"] )) . "23:59:59";

$resourceIdArray = array();
//$resourceIdArray = array("stampede.tacc.xsede.org_af57850b-103b-49a1-aab2-27cb070d3bd9");
//if( $_POST["resource_ids"] != "" )
	//$resourceIdArray = explode(",", $_POST["resource_ids"]);

//get all resourceIds first -
$query = "SELECT DISTINCT s.EXPERIMENT_ID, s.STATE,s.STATUS_UPDATE_TIME, c.RESOURCE_HOST_ID FROM STATUS s, COMPUTATIONAL_RESOURCE_SCHEDULING c  WHERE s.EXPERIMENT_ID=c.EXPERIMENT_ID AND STATUS_TYPE='EXPERIMENT' AND ( STATUS_UPDATE_TIME BETWEEN '" . $startDate . "' AND '" . $endDate . "') GROUP BY c.RESOURCE_HOST_ID";
$completedJobs = mysqli_query( $con, $query);
$completedJobsCount = 0;
while($row = mysqli_fetch_array($completedJobs)) {
  $resourceIdArray[] = $row["RESOURCE_HOST_ID"];
}

//var_dump( $resourceIdArray); exit;

$canJobs = "";
$lJobs = "";
$eJobs = "";
$fJobs = "";
$cJobs = "";
$tJobs = "";

foreach( $resourceIdArray as $resourceId)
{
	//getting status of all jobs
	$canceledJobsCount = 0;
	$launchedJobsCount = 0;
	$executingJobsCount = 0;
	$canceledJobsCount = 0;
	$failedJobsCount = 0;
	$completedJobsCount = 0;
	$createdJobsCount = 0;

	$jobStatus = mysqli_query( $con, "SELECT DISTINCT s.EXPERIMENT_ID, s.STATE,s.STATUS_UPDATE_TIME, c.RESOURCE_HOST_ID FROM STATUS s, COMPUTATIONAL_RESOURCE_SCHEDULING c  WHERE s.EXPERIMENT_ID=c.EXPERIMENT_ID AND STATUS_TYPE='EXPERIMENT' AND ( STATUS_UPDATE_TIME BETWEEN '" . $startDate . "' AND '" . $endDate . "') AND c.RESOURCE_HOST_ID='" . $resourceId . "'");

	while($row = mysqli_fetch_array($jobStatus)) {

		if( $row["STATE"] == "CANCELED")
	  		$canceledJobsCount++;
	  	else if( $row["STATE"] == "LAUNCHED")
	  		$launchedJobsCount++;
	  	else if ($row["STATE"] == "EXECUTING")
	  		$executingJobsCount++;
	  	else if( $row["STATE"] == "FAILED")
	  		$failedJobsCount++;
	  	else if( $row["STATE"] == "COMPLETED")
	  		$completedJobsCount++;
	  	else if( $row["STATE"] == "CREATED")
	  		$createdJobsCount++;
	}

	$canJobs .= $canceledJobsCount . ",";
	$lJobs .= $launchedJobsCount . ",";
	$eJobs .= $executingJobsCount . ",";
	$fJobs .= $failedJobsCount . ",";
	$cJobs .= $completedJobsCount . ",";



	//total jobs
	$totalJobs = mysqli_query( $con, "SELECT DISTINCT e.EXPERIMENT_ID,e.CREATION_TIME, c.RESOURCE_HOST_ID FROM EXPERIMENT e, COMPUTATIONAL_RESOURCE_SCHEDULING c  WHERE e.EXPERIMENT_ID=c.EXPERIMENT_ID  AND (e.CREATION_TIME BETWEEN '" . $startDate . "' AND '" . $endDate . "') AND c.RESOURCE_HOST_ID='" . $resourceId . "'");
	$totalJobsCount = 0;
	while($row = mysqli_fetch_array($totalJobs)) {
	  $totalJobsCount++;
	}
	//total jobs - jobs that are only created and not running.
	$totalJobsCount = $totalJobsCount - $createdJobsCount;
	$tJobs .= $totalJobsCount . ",";

}

header("Location:index.php?server_type=" . $_POST["server_type"] .
							"&start_date=" . $_POST["start_date"] . 
							"&end_date=" . $_POST["end_date"] .
							"&canJobs=" . $canJobs . 
							"&lJobs=" . $lJobs .
							"&eJobs=" . $eJobs .
							"&fJobs=" . $fJobs .
							"&cJobs=" . $cJobs .
							"&tJobs=" . $tJobs .
							"&resource_ids=" . implode(",", $resourceIdArray)
		);

mysqli_close($con);
?> 