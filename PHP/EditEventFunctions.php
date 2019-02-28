<?php

function getDetails(){
    global $eventID, $table;
    $eventDetails = sqlFetch("SELECT * FROM ".$table." WHERE eventID = '".$eventID."'", "ASSOC");
    $eventDetails = $eventDetails[0];
    return $eventDetails;
}

function fieldHasValue($value){
    if($value){
        echo 'value="'.$value.'"';
    }
}

function fieldHasThisValue($query, $value){
    if($query == $value){
        echo "selected='selected'";
    }
}


function fieldIsChecked($query, $value){
    if($query == $value){
        echo "checked";
    }
}


function fieldIsCheckedArray($query, $value){
    $query = json_decode($query);
    if(in_array($value, $query)){
        echo "checked";
    }
}


function isCardiffEvent($selection){
    $selection = decrypt($selection);

    if($selection == "CU Open Day" || $selection == "CU UCAS Day" || $selection == "Networking Event"){
        return true;
    }
    else{
        return false;
    }
}


function checkEventCompletenessIDOnly($eventID){
    $eventPrimary = sqlFetch("SELECT eventName, fundingSource, eventDate, startTime, endTime, type FROM EventPrimary WHERE eventID = '".$eventID."'", "ASSOC");

    $eventLocation = sqlFetch("SELECT address1, postcode, transport FROM EventLocation WHERE eventID = '".$eventID."'", "ASSOC");

    $eventWorkshop = sqlFetch("SELECT topic FROM EventWorkshop WHERE eventID = '".$eventID."'", "ASSOC");

    $eventAmbassadors = sqlFetch("SELECT numberNeeded, trainingRequired, reportLocation FROM EventAmbassadors WHERE eventID = '".$eventID."'", "ASSOC");

    $returnValue = checkEventCompleteness($eventPrimary[0], $eventLocation[0], $eventWorkshop[0], $eventAmbassadors[0]);
    return $returnValue;
}


function checkEventCompleteness($eventPrimary, $eventLocation, $eventWorkshop, $eventAmbassadors){

    if($eventPrimary["eventName"] == "" || $eventPrimary["fundingSource"] == "" || $eventPrimary["eventDate"] == "" || $eventPrimary["startTime"] == "" || $eventPrimary["endTime"] == "" || $eventPrimary["type"] == ""){
        return false;
    }
    
    if( ($eventLocation["address1"] == "" && $eventLocation["postcode"] == "") || $eventLocation["transport"] == ""){
        return false;
    }

    $eventType = decrypt($eventPrimary["type"]);

    if( ($eventType == "Primary School" || $eventType == "Secondary School") && $eventWorkshop["yearGroup"] == "" ){
        return false;
    }
    else if( ($eventType == "CPD" || $eventType == "College") && $eventWorkshop["level"] == ""){
        return false;
    }
    
    if(isCardiffEvent($eventPrimary["type"]) == false){

        if($eventWorkshop["topic"] == ""){
            return false;
        }

    }

    if($eventAmbassadors["numberNeeded"] == "" || $eventAmbassadors["trainingRequired"] == "" || $eventAmbassadors["reportLocation"] == ""){
        return false;
    }

    //If all tests passed (I.e. not returned false) then return true
    return true;
}


?>