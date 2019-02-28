<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";
require_once "/var/www/html/PHP/FormFunctions.php";
require_once "/var/www/html/PHP/SQLFunctions.php";


if($_SERVER["REQUEST_METHOD"] === "POST"){

    $throwbackError = NULL;

    require "/var/www/html/PHP/Scripts/AddEventManagePost.php";

    #Format event topic
    if($eventTopic != ""){
        $array = array();

        foreach($eventTopic as $topic){
            $array[] = (validateInput($topic));
        }

        $eventTopic = json_encode($array);
    }


    #Format year group
    if($yearGroup != ""){
        $yearGroup = encryptJSON($yearGroup);
    }


    #Set event date
    if($repeatPosition > 0){

        if($repeatFrequency == "daily"){
            $value = 1 * $repeatPosition;
        }
        elseif($repeatFrequency == "weekly"){
            $value = 7 * $repeatPosition;
        }
        elseif($repeatFrequency == "fortnightly"){
            $value = 14 * $repeatPosition;

        }
        $dateInterval = "P".$value."D";

        $_eventDate = new DateTime($eventDate);
        $_eventDate->add(new DateInterval($dateInterval));
        $eventDate = date_format($_eventDate, 'Y-m-d');

    }



    #Check event does not already exist
    $checkAlreadyExists = sqlFetch("SELECT eventID, eventName, type FROM EventPrimary WHERE eventDate".isNull($eventDate)." AND startTime".isNull($startTime)." AND endTime".isNull($endTime), "ASSOC");

    if(isset($checkAlreadyExists) && count($checkAlreadyExists) > 0){

        $checkEventName = decrypt($eventName);
        $checkEventType = decrypt($eventType);

        foreach($checkAlreadyExists as $eventToCheck){
            $_checkEventName = decrypt($eventToCheck["eventName"]);
            $_checkEventType = decrypt($eventToCheck["type"]);

            if($checkEventName == $_checkEventName && $checkEventType == $_checkEventType){
                $throwbackError = "The event you are attempting to create already exists";
            }
        }
    }

    if($throwbackError == NULL){

        #Insert to EventPrimary
        if(sqlInsert("INSERT INTO EventPrimary(eventName, project, fundingSource, eventDate, startTime, endTime, type, adminID, visibility) VALUES ('".$eventName."', ".hasValue($project).", '".$fundingSource."', ".hasValue($eventDate).", ".hasValue($startTime).", ".hasValue($endTime).", '".$eventType."', '".$adminID."', '".$visibility."')", True, True) == False){
            $throwbackError = "A system error has occured whilst writing to the database. Please seek help from the system adminstrator and quote the following error code: 9-P-AEES-1.";
        }
        else{

            #Retrive ID of created event
            $eventID = sqlFetch("SELECT eventID FROM EventPrimary WHERE eventName='".$eventName."' AND project".isNull($project)." AND fundingSource='".$fundingSource."' AND eventDate".isNull($eventDate)." AND startTime".isNull($startTime)." AND endTime".isNull($endTime)." AND type='".$eventType."'", "ASSOC");

            if(count($eventID) == 0){
                $throwbackError = "A system error has occured whilst generating event ID. Please seek help from the system adminstrator and quote the following error code: 9-P-AEES-2.";
            }
            else{
    
                $eventID = $eventID[0]["eventID"];
    
                
                #Insert to EventLocation
                if(sqlInsert("INSERT INTO EventLocation(eventID, address1, address2, county, postcode, transport) VALUES ('".$eventID."', '".$address1."', ".hasValue($address2).", ".hasValue($county).", ".hasValue($postcode).", '".$transport."')", True, True) == False){
                    $throwbackError = "A system error has occured whilst writing to the database. Please seek help from the system adminstrator and quote the following error code: 9-P-AEES-3.";
                }
                else{

                    #Insert to EventWorkshop
                    if(sqlInsert("INSERT INTO EventWorkshop(eventID, yearGroup, numberOfParticipants, level, topic) VALUES ('".$eventID."', ".hasValue($yearGroup).", ".hasValue($numberOfParticipants).", ".hasValue($level).", ".hasValue($eventTopic).")", True, True) == False){
                        $throwbackError = "A system error has occured whilst writing to the database. Please seek help from the system adminstrator and quote the following error code: 9-P-AEES-4.";
                    }
                    else{

                        #Insert to EventAmbassadors
                        if(sqlInsert("INSERT INTO EventAmbassadors(eventID, numberNeeded, trainingRequired, leadAmbassador, reportLocation) VALUES ('".$eventID."', ".hasValue($numberNeeded).", '".$trainingRequired."', ".hasValue($leadAmbassador).", '".$reportLocation."')", True, True) == False){
                            $throwbackError = "A system error has occured whilst writing to the database. Please seek help from the system adminstrator and quote the following error code: 9-P-AEES-5.";
                        }
                        else{

                            #Insert to EventContact
                            if(sqlInsert("INSERT INTO EventContact(eventID, name, email, phoneNo) VALUES ('".$eventID."', ".hasValue($contactName).", ".hasValue($contactEmail).", ".hasValue($contactPhoneNo).")", True, True) == False){
                                $throwbackError = "A system error has occured whilst writing to the database. Please seek help from the system adminstrator and quote the following error code: 9-P-AEES-6.";
                            }
                            else{

                                #Insert to EventAdditionalInformation
                                if(sqlInsert("INSERT INTO EventAdditionalInformation(eventID, resourcesRequired, additionalInformation) VALUES ('".$eventID."', ".hasValue($resourcesRequired).", ".hasValue($additionalInformation).")", True, True) == False){
                                    $throwbackError = "A system error has occured whilst writing to the database. Please seek help from the system adminstrator and quote the following error code: 9-P-AEES-7.";
                                }
                                else{

                                    #Event Creation Successful - Redirect to Event Page
                                    header("location: /Admin/Event.php?id=".$eventID);

                                }
                            }
                        }
                    }
                }
    
            }
        }
    
    }

    if($throwbackError != NULL && isset($eventID)){
        sqlDelete("DELETE FROM EventPrimary WHERE eventID = '".$eventID."'");
        sqlDelete("DELETE FROM EventLocation WHERE eventID = '".$eventID."'");
        sqlDelete("DELETE FROM EventWorkshop WHERE eventID = '".$eventID."'");
        sqlDelete("DELETE FROM EventAmbassadors WHERE eventID = '".$eventID."'");
        sqlDelete("DELETE FROM EventContact WHERE eventID = '".$eventID."'");
        sqlDelete("DELETE FROM EventAdditionalInformation WHERE eventID = '".$eventID."'");
    }
    if($throwbackError != NULL){
        echo "<p style='color: red;'><strong>".$throwbackError."</strong></p>";
    }
    else{
        echo $eventID;
    }
}

?>