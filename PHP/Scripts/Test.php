<?php

require_once "/var/www/html/PageComponents/Head.php";

function logSystemError($reportedFrom, $reportDetails){
	$reportedAt = date("Y-m-d H:i:s");
	sqlInsert("INSERT INTO BugReports(reportedFrom, reportedAt, reportedBy, reportDetails) VALUES ('".$reportedFrom."', '".$reportedAt."', 'SYSTEM', '".encrypt($reportDetails)."')");
}

logSystemError("TEST", "TEST");

?>