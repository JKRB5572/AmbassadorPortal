<?php

require_once "/var/www/html/PageComponents/Requirements.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST["metricName"])){ $metricName = validateInput($_POST["metricName"]); } else { return; }
    if(isset($_POST["errorLocation"])){ $errorLocation = validateInput($_POST["errorLocation"]); } else { $errorLocation = "/PHP/Metrics/Increment.php"; }
    if(isset($_POST["errorMessage"])){ $errorMessage = validateInput($_POST["errorMessage"]); } else { $errorMessage = "Failed to increment metric"; }


    $value = sqlFetch("SELECT value FROM Metrics WHERE metricName = '".$metricName."'", "NUM");
    $value = $value[0][0];
    $value++;
    
    if(sqlUpdate("UPDATE Metrics SET value = ".$value." WHERE metricName = '".$metricName."'", True, True) == False){
        logSystemError($errorLocation, $errorMessage);
    }

}

?>