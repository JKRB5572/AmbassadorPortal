<?php

require_once "../PHP/Config.php";
require_once "../PHP/CoreFunctions.php";

if(!$isDeveloperPane){
    header("location: index.php");
}

$cronRecords = sqlFetch("SELECT * FROM CronRecords", "ASSOC");

?>

<h4>Cron Job Log</h4>

<table class="fullwidth">
    <tr>
        <th>Job</th>
        <th>Last Completed</th>
        <th>Error Report</th>
    </tr>

    <?php

    foreach($cronRecords as $record){
        $error = $record["errorReport"];
        if($error == "No Errors"){
            $error = "<span style='color: green;'>".$error."</span>";
        }
        else{
            $error = "<strong><span style='color: red;'>".$error."</span></strong>";
        }
        echo "
        <tr>
            <td>".$record["jobName"]."</td>
            <td>".$record["timestamp"]."</td>
            <td>".$error."</td>
        </tr>
        ";
    }

    ?>

</table>