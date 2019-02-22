<?php

require_once "/var/www/html/PageComponents/Requirements.php";


function job(){
    $results = sqlFetch("SELECT Jobshop.universityID as universityID, surname, forename, givenName FROM Jobshop, Ambassadors WHERE Jobshop.universityID = Ambassadors.universityID AND jobshopConfirmed = 0", "ASSOC");

    $existingRecords = sqlFetch("SELECT recordID, category FROM NotificationsAdmin WHERE category = 'jobshop'", "ASSOC");

    if(count($results) == 1){
        $notificationText = "There is 1 ambassador awaiting Jobshop card confirmation.";
    }
    else{
        $notificationText = "There are ".count($results)." ambassadors awaiting Jobshop card confirmation.";
    }


    if(count($existingRecords) > 0 && count($results) > 0){
        sqlUpdate("UPDATE NotificationsAdmin SET text = '".$notificationText."' WHERE recordID = '".$existingRecords[0]["recordID"]."'");
    }
    else if(count($existingRecords) == 0 && count($results) > 0){
        sqlInsert("INSERT INTO NotificationsAdmin(audienceAccessLevel, text, category) VALUES (1, '".$notificationText."', 'jobshop')");
    }
    else if(count($existingRecords) > 0 && count($results) == 0){
        sqlDelete("DELETE FROM NotificationsAdmin WHERE recordID = '".$existingRecords[0]["recordID"]."'");
    }

    return True;
}

if(job()){
    sqlUpdate("UPDATE CronRecords SET timestamp = '".date_format(date_create(), 'Y-m-d H:i:s')."', errorReport = 'No Errors' WHERE jobName = 'Update Jobshop'");
}
else{
    sqlUpdate("UPDATE CronRecords SET timestamp = '".date_format(date_create(), 'Y-m-d H:i:s')."', errorReport = 'Failed to execute script' WHERE jobname = 'Update Jobshop'");
}

?>