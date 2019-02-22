<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";
require_once "/var/www/html/PHP/FormFunctions.php";
require_once "/var/www/html/PHP/SQLFunctions.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $eventID = validateInput($_POST["eventID"]);

    $eventDetails = sqlFetch("SELECT eventName, eventDate FROM EventPrimary WHERE eventID = '".$eventID."'", "ASSOC");
    $eventDetails = $eventDetails[0];

    $postArray = validateInput($_POST["postArray"]);
    $postArray = explode(",", $postArray);


    $previouslySelectedAmbassadors = sqlFetch("SELECT universityID FROM EventRegistration WHERE eventID = '".$eventID."' AND requestedStartTime IS NOT NULL", "NUM");

    $selectedAmbassadors = array();


    for($i = 0; $i < sizeof($postArray); $i+=3){
        sqlupdate("UPDATE EventRegistration SET requestedStartTime = '".$postArray[$i+1]."', requestedEndTime = '".$postArray[$i+2]."' WHERE eventID = '".$eventID."' AND universityID = '".$postArray[$i]."'");

        $notificationText = "You have been selected to work the event '".decrypt($eventDetails["eventName"])."' on ".$eventDetails["eventDate"]." starting at ".substr($postArray[$i+1], 0, 5)." and ending at ".substr($postArray[$i+2], 0, 5)."<br/>If you are no longer able to work this event please request to withdraw.";

        createNotificationAmbassador($notificationText, "eventSelection", $postArray[$i]);

        array_push($selectedAmbassadors, $postArray[$i]);
    }

    foreach($previouslySelectedAmbassadors as $previousAmbassador){
        if(!in_array($previousAmbassador[0], $selectedAmbassadors)){
            sqlUpdate("UPDATE EventRegistration SET requestedStartTime = null, requestedEndTime = null WHERE eventID = '".$eventID."' AND universityID = '".$previousAmbassador[0]."'");

            $notificationText = "You have been removed from and therefore no longer selected to work the event '".decrypt($eventDetails["eventName"])."' on ".$eventDetails["eventDate"].".<br/>If you do not understand or wish to query why you have been removed from this event please send an email to comscambassadors@cardiff.ac.uk seeking claification.";
            
            createNotificationAmbassador($notificationText, "eventDeselection", $previousAmbassador[0]);
        }
    }

    $url = "http://".$_SERVER["HTTP_HOST"]."/PHP/Scripts/SelectEventAmbassadorsDatabaseManagement.php";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "id=".$eventID);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

?>