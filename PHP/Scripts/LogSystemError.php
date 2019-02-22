<?php

require_once "/var/www/html/PHP/SQLFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";

function logSystemError($reportedFrom, $reportDetails){
	$reportedAt = date("Y-m-d H:i:s");
	sqlInsert("INSERT INTO BugReports(reportedFrom, reportedAt, reportedBy, reportDetails) VALUES ('".$reportedFrom."', '".$reportedAt."', 'SYSTEM', '".encrypt($reportDetails)."')");
}

?>