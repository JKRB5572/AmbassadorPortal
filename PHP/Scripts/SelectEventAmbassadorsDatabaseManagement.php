<?php

require_once "/var/www/html/PageComponents/Requirements.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $eventID = validateInput($_POST["id"]);

    $eventRegistration = sqlFetch("SELECT universityID as id, requestedStartTime as startTime, requestedEndTime as endTime FROM EventRegistration WHERE eventID = '".$eventID."'", "ASSOC");

    sqlDelete("DELETE FROM EventRegistrationHistory WHERE eventID = '".$eventID."'");
    sqlDelete("DELETE FROM AmbassadorEventHistory WHERE eventID = '".$eventID."'");


    foreach($eventRegistration as $registeredAmbassador){

        $hours = sqlFetch("SELECT hoursWorked FROM Ambassadors WHERE universityID = '".$registeredAmbassador["id"]."'", "NUM");

        if($registeredAmbassador["startTime"]){
            $ambassadorSelected = 1;
        }
        else{
            $ambassadorSelected = 0;
        }

        $query = "INSERT INTO EventRegistrationHistory(eventID, universityID, ambassadorSelected, hoursAtSelection) VALUES('".$eventID."', '".$registeredAmbassador["id"]."', ".$ambassadorSelected.", ".$hours[0][0].")";
        if(sqlInsert($query, True, True) == False){
            logSystemError("/PHP/Scripts/SelectEventAmbassadorsDatabaseManagement", "Error performing sql insert with query: ".$query);
        }
        else{
            echo $query;
        }

        $query = "INSERT INTO AmbassadorEventHistory(eventID, universityID, requestedStartTime, requestedEndTime) VALUES ('".$eventID."', '".$registeredAmbassador["id"]."', '".$registeredAmbassador["startTime"]."', '".$registeredAmbassador["endTime"]."')";
        if(sqlInsert($query, True, True) == False){
            logSystemError("/PHP/Scripts/SelectEventAmbassadorsDatabaseManagement", "Error performing sql insert with query: ".$query);
        }

    }

}