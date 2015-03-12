<?php

// Create connection
$con=mysqli_connect("gw85.iu.xsede.org","jobstatus","jobstatus345","airavata_gta_dev", 3306);

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

$fJobs = "";
$cJobs = "";
$tJobs = "";
$canJobs = "";

foreach( $resourceIdArray as $resourceId)
{
	//all completed jobs
	$query = "SELECT DISTINCT s.EXPERIMENT_ID, s.STATE,s.STATUS_UPDATE_TIME, c.RESOURCE_HOST_ID FROM STATUS s, COMPUTATIONAL_RESOURCE_SCHEDULING c  WHERE s.EXPERIMENT_ID=c.EXPERIMENT_ID AND STATUS_TYPE='EXPERIMENT' AND ( STATUS_UPDATE_TIME BETWEEN '" . $startDate . "' AND '" . $endDate . "') AND STATE='COMPLETED' AND c.RESOURCE_HOST_ID='" . $resourceId . "'";
	$completedJobs = mysqli_query( $con, $query);
	$completedJobsCount = 0;
	while($row = mysqli_fetch_array($completedJobs)) {
	  $completedJobsCount++;
	}

	$cJobs .= $completedJobsCount . ",";

	//all canceled jobs
	$canceledJobs = mysqli_query( $con, "SELECT DISTINCT s.EXPERIMENT_ID, s.STATE,s.STATUS_UPDATE_TIME, c.RESOURCE_HOST_ID FROM STATUS s, COMPUTATIONAL_RESOURCE_SCHEDULING c  WHERE s.EXPERIMENT_ID=c.EXPERIMENT_ID AND STATUS_TYPE='EXPERIMENT' AND ( STATUS_UPDATE_TIME BETWEEN '" . $startDate . "' AND '" . $endDate . "') AND STATE='CANCELED' AND c.RESOURCE_HOST_ID='" . $resourceId . "'");
	$canceledJobsCount = 0;
	while($row = mysqli_fetch_array($canceledJobs)) {
	  $canceledJobsCount++;
	}
	$canJobs .= $canceledJobsCount . ",";



	//all failed jobs
	$failedJobs = mysqli_query( $con, "SELECT DISTINCT s.EXPERIMENT_ID, s.STATE,s.STATUS_UPDATE_TIME, c.RESOURCE_HOST_ID FROM STATUS s, COMPUTATIONAL_RESOURCE_SCHEDULING c  WHERE s.EXPERIMENT_ID=c.EXPERIMENT_ID AND STATUS_TYPE='EXPERIMENT' AND ( STATUS_UPDATE_TIME BETWEEN '" . $startDate . "' AND '" . $endDate . "') AND STATE='FAILED' AND c.RESOURCE_HOST_ID='" . $resourceId . "'");
	$failedJobsCount = 0;
	while($row = mysqli_fetch_array($failedJobs)) {
	  $failedJobsCount++;
	}
	$fJobs .= $failedJobsCount . ",";


	//total jobs
	$totalJobs = mysqli_query( $con, "SELECT DISTINCT e.EXPERIMENT_ID,e.CREATION_TIME, c.RESOURCE_HOST_ID FROM EXPERIMENT e, COMPUTATIONAL_RESOURCE_SCHEDULING c  WHERE e.EXPERIMENT_ID=c.EXPERIMENT_ID  AND (e.CREATION_TIME BETWEEN '" . $startDate . "' AND '" . $endDate . "') AND c.RESOURCE_HOST_ID='" . $resourceId . "'");
	$totalJobsCount = 0;
	while($row = mysqli_fetch_array($totalJobs)) {
	  $totalJobsCount++;
	}
	$tJobs .= $totalJobsCount . ",";

}

header("Location:index.php?start_date=" . $_POST["start_date"] . 
							"&end_date=" . $_POST["end_date"] .
							"&cJobs=" . $cJobs . 
							"&fJobs=" . $fJobs .
							"&tJobs=" . $tJobs .
							"&canJobs=" . $canJobs .
							"&resource_ids=" . implode(",", $resourceIdArray)
		);

mysqli_close($con);
?> 