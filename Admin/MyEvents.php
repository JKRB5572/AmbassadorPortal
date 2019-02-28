<?php

include "../PageComponents/Head.php";

checkPageIsActive("MyEventsAdmin");

if(!isset($_SESSION["leadAmbassador"]) && $_SESSION["leadAmbassador"] != "true"){
    header("location: Home.php");
}



function checkAction($array, $message, $action){
    if(count($array) > 0){
        return "<td class='actionRequiredNone'>None</td>";
    }
    else{
        return "<td class='actionRequiredUrgent' onclick='location.replace(\"".$action."\")'>".$message."</td>";
    }
}


function eventName($eventName){
    return "<td>".decrypt($eventName)."</td>";
}


function dateAndTime($eventDate, $startTime, $endTime){
    return "<td>".verboseDateHTML($eventDate)."<br/><em>".substr($startTime, 0, 5)." - ".substr($endTime, 0, 5)."</em></td>";
}


function location($address, $postcode){
    $returnString = "<td>";

    if($address && $postcode){
        $returnString .= decrypt($address)."<br/>".decrypt($postcode);
    }
    else if($address){
        $returnString .= decrypt($address);
    }
    else if($postcode){
        $returnString .= decrypt($postcode);
    }

    $returnString .= "</td>";
    return $returnString;
}


function transport($transport){
    return "<td>".$transport."</td>";
}


function eventDetails($type, $topic, $level){
    $returnString = "<td>";

    if($type){
        $returnString .= decrypt($type);
        if($topic){
            $returnString .= "<br/><em>".verboseList(fetchTopics($topic))."</em>";
        }
        if($level){
            $returnString .= "<br/>".$level;
        }
    }

    else if($topic){
        $returnString .= "<em>".verboseList(fetchTopcis($topic))."</em>";
        if($level){
            $returnString .= "<br/>".$level;
        }
    }

    else if($level){
        $returnString .= $level;
    }

    $returnString .= "</td>";
    return $returnString;
}


function listAmbassadors($assignedAmbassadors){
    $returnString = "<td>";

    if(count($assignedAmbassadors) > 0){
        foreach($assignedAmbassadors as $ambassador){
            $ambassadorDetails = sqlFetch("SELECT surname, forename, givenName FROM Ambassadors WHERE universityID = '".$ambassador["universityID"]."'", "ASSOC");
            $returnString .= returnFullName($ambassadorDetails[0]["surname"], $ambassadorDetails[0]["forename"], $ambassadorDetails[0]["givenName"], True) . "<br/>";
        }
    }
    else{
        $returnString .= "No ambassadors assigned";
    }

    $returnString .= "</td>";
    return $returnString;
}


function classDetails($contactName, $yearGroup){
    $returnString = "<td>";

    if($contactName && $yearGroup){
        $returnString .= decrypt($contactName)."<br/><em>".decrypt($yearGroup)."</em>";
    }
    else if($contactName){
        $returnString .= decrypt($contactName);
    }
    else if($yearGroup){
        $returnString .= "<em>".decrypt($yearGroup)."</em>";
    }

    $returnString .= "</td>";
    return $returnString;
}


function resourcesRequired($resourcesRequired){
    return "<td>".decrypt($resourcesRequired)."</td>";
}



?>

<div class="my-events">

<h2>My Events</h2>

<?php

//SQL Fetch events from today onwards
$upcomingEvents = sqlFetch("SELECT eventID, eventName, startTime, endTime, eventDate, type, address1, postcode, transport, level, topic, yearGroup, name AS contactName, resourcesRequired, additionalInformation
FROM EventPrimary
JOIN EventLocation USING (eventID)
JOIN EventWorkshop USING (eventID)
JOIN EventAmbassadors USING (eventID)
JOIN EventContact USING (eventID)
JOIN EventAdditionalInformation USING (eventID)
WHERE eventDate >= '".todayDate."'
AND leadAmbassador = '".$_SESSION["userID"]."'
ORDER BY eventDate ASC"
, "ASSOC");


//Create date variable for yesterday in required YYYY-MM-DD format
$yesterday = date_create(todayDate);
date_sub($yesterday, date_interval_create_from_date_string("1 day"));
$yesterday = date_format($yesterday, "Y-m-d");


//Create date variable for last year in required YYYY-MM-DD format
$lastYear = date_create(todayDate);
date_sub($lastYear, date_interval_create_from_date_string("365 days"));
$lastYear = date_format($lastYear, "Y-m-d");

//SQL Fetch events from last year to

$previousEvents = sqlFetch("SELECT eventID, eventName, startTime, endTime, eventDate, type, address1, postcode, transport, level, topic, yearGroup, name AS contactName, resourcesRequired, additionalInformation
FROM EventPrimary
JOIN EventLocation USING (eventID)
JOIN EventWorkshop USING (eventID)
JOIN EventAmbassadors USING (eventID)
JOIN EventContact USING (eventID)
JOIN EventAdditionalInformation USING (eventID)
WHERE leadAmbassador = '{$_SESSION["userID"]}' 
AND eventDate BETWEEN CAST('{$lastYear}' AS datetime) AND CAST('{$yesterday}' AS datetime) 
ORDER BY eventDate DESC", "ASSOC");

?>

<p><em>Only events where you are/were the designated lead ambassador are shown below.</em></p>

<h4>Upcoming Events</h4>

<?php

if(count($upcomingEvents) > 0){

    echo "
    <table class='lead-ambassador-upcoming-events'>
        <tr>
            <th>Required Actions</th>
            <th>Event Name</th>
            <th>Date & Time</th>
            <th>Location</th>
            <th>Transport</th>
            <th>Event Details</th>
            <th>Workshop Details</th>
            <th>Ambassadors</th>
            <th>Resources Requried</th>
        </tr>";

    foreach($upcomingEvents as $event){

        $assignedAmbassadors = sqlFetch("SELECT universityID FROM EventRegistration WHERE requestedStartTime IS NOT NULL and eventID = '".$event["eventID"]."'", "ASSOC");

        echo "<tr>";

        echo checkAction($assignedAmbassadors, "Assign Ambassadors", "/Admin/EventAmbassadors.php?id=".$event["eventID"]);
        echo eventName($event["eventName"]);
        echo dateAndTime($event["eventDate"], $event["startTime"], $event["endTime"]);
        echo location($event["address1"], $event["postcode"]);
        echo transport($event["transport"]);
        echo eventDetails($event["type"], $event["topic"], $event["level"]);
        echo classDetails($event["contactName"], $event["yearGroup"]);
        echo listAmbassadors($assignedAmbassadors);
        echo resourcesRequired($event["resourcesRequired"]);

        echo "</tr>";

    }

    echo "</table>";

}

else{
    echo "<p>You have no upcoming events</p>";
}

?>

<h4>Previous Events</h4>

<?php

if(count($previousEvents) > 0){
    echo "
    <table class='previous-events-lead-ambassador'>
        <tr>
            <th>Required Actions</th>
            <th>Event Name</th>
            <th>Date & Time</th>
            <th>Location</th>
            <th>Event Details</th>
        </tr>";
    
    foreach($previousEvents as $event){

        $attendance = sqlFetch("SELECT ambassadorAttended FROM AmbassadorEventHistory WHERE eventID = '".$event["eventID"]."'", "ASSOC");

        echo "<tr>";

        echo checkAction($attendance, "Complete Attendance", "");
        echo eventName($event["eventName"]);
        echo dateAndTime($event["eventDate"], $event["startTime"], $event["endTime"]);
        echo location($event["address1"], $event["postcode"]);
        echo eventDetails($event["type"], $event["topic"], $event["level"]);

        echo "</tr>";
    }

    echo "</table>";    
}
else{
    echo "<p>You have no previous events from the last year</p>";
}

?>

</div><!-- my-events -->

<?php

include "../PageComponents/Foot.php";

?>