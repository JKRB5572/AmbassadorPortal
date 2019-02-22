<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";
require_once "/var/www/html/PHP/FormFunctions.php";
require_once "/var/www/html/PHP/SQLFunctions.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $eventID = validateInput($_POST["eventID"]);
    $universityID = validateInput($_POST["universityID"]);
    $alreadyExists = sqlFetch("SELECT requestedStartTime FROM EventRegistration WHERE eventID = '".$eventID."' AND universityID = '".$universityID."'", "ASSOC");
    $alreadyExists = $alreadyExists[0]; //Remove Outer Array
    if(count($alreadyExists) == 0){
        sqlInsert("INSERT INTO EventRegistration(eventID, universityID) VALUES (".$eventID.", '".$universityID."')", true);
    }
    else{

        if($alreadyExists["requestedStartTime"]){

            $eventDetails = sqlFetch("SELECT eventName, eventDate, leadAmbassador FROM Events WHERE eventID = '".$eventID."'", "ASSOC");
            $eventDetails = $eventDetails[0]; //Remove Outer Array
            $ambassadorDetails = sqlFetch("SELECT surname, forename, givenName FROM Ambassadors WHERE universityID = '".$universityID."'", "ASSOC");
            $ambassadorDetails = $ambassadorDetails[0]; //Remove Outer Array

            $notificationText = returnFullName($ambassadorDetails["surname"], $ambassadorDetails["forename"], $ambassadorDetails["givenName"], True)." can no longer work event ".decrypt($eventDetails["eventName"])." on ".$eventDetails["eventDate"]."."; 
            sqlInsert("INSERT INTO Notifications(audienceAccessLevel, targetIndividual, text, category) VALUES (1, '".$eventDetails["leadAmbassador"]."', '".$notificationText."', 'eventAmbassadorWithdrawal')");

        }

        sqlDelete("DELETE FROM EventRegistration WHERE eventID='".$eventID."' AND universityID = '".$universityID."'");
        sqlDelete("DELETE FROM EventRegistrationHistory WHERE eventID='".$eventID."' AND universityID = '".$universityID."'");
        sqlDelete("DELETE FROM AmbassadorEventHistory WHERE eventID='".$eventID."' AND universityID = '".$universityID."'");

    }

}
else{
    header("Location: ../../Ambassador/Home.php");
}

?>