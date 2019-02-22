<?php

require_once "/var/www/html/PageComponents/Requirements.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $eventID = validateInput($_POST["eventID"]);

    $eventDetails = sqlFetch("SELECT eventName, eventDate FROM EventPrimary WHERE eventID = '".$eventID."'", "ASSOC");
    $eventDetails = $eventDetails[0];

    $postArray = validateInput($_POST["postArray"]);
    $postArray = explode(",", $postArray);

    if(isset($_POST["edit"]) && validateInput($_POST["validateInput"]) == "edit"){
        $edit = true;
    }
    else{
        $edit = false;
    }


    $error = false;

    for($i = 0; $i < sizeof($postArray); $i+=4){

        $timesheetStatus = sqlFetch("SELECT ambassadorAttended, timesheetSigned FROM AmbassadorEventHistory WHERE eventID = '".$eventID."' AND universityID = '".$postArray[$i]."'", "ASSOC")

        $requestedTimes = sqlFetch("SELECT requestedStartTime as startTime, requestedEndTime as endTime FROM EventRegistration  WHERE eventID = '".$eventID."' AND universityID = '".$postArray[$i]."'", "ASSOC");

        if($postArray[$i+1] == "true"){
            $attended = 1;
        }
        else{
            $attended = 0;
        }

        $query = "INSERT INTO AmbassadorEventHistory(eventID, universityID, requestedStartTime, requestedEndTime, ambassadorAttended, recordedStartTime, recordedEndTime) VALUES('".$eventID."', '".$postArray[$i]."', '".$requestedTimes[0]["startTime"]."', '".$requestedTimes[0]["endTime"]."', ".$attended.", '".$postArray[$i+2]."', '".$postArray[$i+3]."')";

        if(sqlInsert($query, True, True) == False){
            $error = true;
            logSystemError("/PHP/Scripts/LogTimesheets.php", "Error executing sql query: ".$query); 
            break;
        }
    }

    if($error == false){
        sqlDelete("DELETE FROM EventRegistration WHERE eventID = '".$eventID."'");
        echo "Operation Complete";
    }
    else{
        echo "A system error has occured whilst writing to the database. Please seek help from the system adminstrator and quote the following error code: 9-P-LT-0.";
    }
}