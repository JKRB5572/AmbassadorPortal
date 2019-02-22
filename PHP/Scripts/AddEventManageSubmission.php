<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";
require_once "/var/www/html/PHP/FormFunctions.php";
require_once "/var/www/html/PHP/SQLFunctions.php";


if($_SERVER["REQUEST_METHOD"] === "POST"){

    $throwbackError = NULL;

    require "/var/www/html/PHP/Scripts/AddEventManagePost.php";

    //Set number of events to 1 if no repeat frequency is provided
    if($repeatFrequency == ""){
       $numberOfEvents = 1; 
    }

    //Create array to store generated event ids
    $createdEventsIDs = array();


    //Create string for POST header
    $postString = "";

    foreach($_POST as $key => $value){
        if(is_array($value)){
            $value = json_encode($value);
        }
        $postString .= $key."=".$value."&";
    }

    $postString = substr($postString, 0, -1);


    //Send each event to AddEventExecuteSubmission.php recording created ID's and errors
    for($i = 0; $i < $numberOfEvents; $i ++){

        $url = "http://".$_SERVER["HTTP_HOST"]."/PHP/Scripts/AddEventExecuteSubmission.php";
        $ch = curl_init($url);
        $postString = http_build_query($_POST, '', '&');
        $postString .= "&adminID=".$_SESSION["userID"];
        $postString .= "&repeatPosition=".$i;
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if(strlen($response) == 11){
            $createdEventsIDs[] = $response;
        }
        else{
            $throwbackError = $response;
        }

    }


    //Update eventPreviousID, eventSubsequentID with relevant eventID's for each event
    for($i = 0; $i <= $numberOfEvents - 1; $i++){

        if($i > 0){

            if(sqlUpdate("UPDATE EventAdditionalInformation SET previousEventID = '".$createdEventsIDs[$i - 1]."' WHERE eventID = '".$createdEventsIDs[$i]."'", True, True) == False){
                $errorText = "A system error has occured whilst linking event nodes. Please seek help from the system adminstrator and quote the following error code: 9-P-ADMS-1.";
                if($throwbackError != NULL){
                    $throwbackError = $errorText."<br/>".$throwbackError;
                    break;
                }
                else{
                    $throwbackError = $errorText;
                    break;
                }
            }

        }

        if($i < $numberOfEvents - 1){

            if(sqlUpdate("UPDATE EventAdditionalInformation SET subsequentEventID = '".$createdEventsIDs[$i + 1]."' WHERE eventID = '".$createdEventsIDs[$i]."'", True, True) == False){
                $errorText = "A system error has occured whilst linking event nodes. Please seek help from the system adminstrator and quote the following error code: 9-P-ADMS-2.";
                if($throwbackError != NULL){
                    $throwbackError = $errorText."<br/>".$throwbackError;
                    break;
                }
                else{
                    $throwbackError = $errorText;
                    break;
                }
            }

        }

    }

    if($throwbackError != NULL){
        echo $throwbackError;
        logSystemError("/Admin/AddEvent", strip_tags($throwbackError));
    }
    else{
        header("location: /Admin/Event.php?id=".$createdEventsIDs[0]);
    }

}