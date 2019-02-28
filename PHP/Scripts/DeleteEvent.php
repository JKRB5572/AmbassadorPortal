<?php

require_once "/var/www/html/PageComponents/Requirements.php";


if($_SERVER["REQUEST_METHOD"] === "POST"){
    $eventID = validateInput($_POST["id"]);
    $connectedNodes = sqlFetch("SELECT previousEventID, subsequentEventID FROM EventAdditionalInformation WHERE eventID = '".$eventID."'", "ASSOC");
    $previousEventID = $connectedNodes[0]["previousEventID"];
    $subsequentEventID = $connectedNodes[0]["subsequentEventID"];

    sqlDelete("DELETE FROM EventPrimary WHERE eventID = '".$eventID."'");
    sqlDelete("DELETE FROM EventLocation WHERE eventID = '".$eventID."'");
    sqlDelete("DELETE FROM EventWorkshop WHERE eventID = '".$eventID."'");
    sqlDelete("DELETE FROM EventAmbassadors WHERE eventID = '".$eventID."'");
    sqlDelete("DELETE FROM EventContact WHERE eventID = '".$eventID."'");

    if($previousEventID){
        if(sqlUpdate("UPDATE EventAdditionalInformation SET subsequentEventID = ".hasValue($subsequentEventID)." WHERE eventID = '".$previousEventID."'", True, True) == False){
            logSystemError("/PHP/Scripts/DeleteEvent", "Failed to successfully collpase and link eventID nodes");
        }
    }
    if($subsequentEventID){
        if(sqlUpdate("UPDATE EventAdditionalInformation SET previousEventID = ".hasValue($previousEventID)." WHERE eventID = '".$subsequentEventID."'", True, True) == False){
            logSystemError("/PHP/Scripts/DeleteEvent", "Failed to successfully collpase and link eventID nodes");
        }
    }
    
    sqlDelete("DELETE FROM EventAdditionalInformation WHERE eventID = '".$eventID."'");

    header("location: /Admin/Calendar.php");
}