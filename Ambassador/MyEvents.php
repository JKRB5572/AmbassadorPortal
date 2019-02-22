<?php

include "../PageComponents/Head.php";

?>

<div class="my-events">
    <h2>My Events</h2>



<?php

$upcomingEvents = sqlFetch("SELECT EventRegistration.*, EventPrimary.eventDate, eventName, level, topic, reportLocation
FROM EventRegistration JOIN EventPrimary USING (eventID)
JOIN EventClass USING (eventID)
JOIN EventAmbassadors USING (eventID)
WHERE requestedStartTime IS NOT NULL
AND EventRegistration.universityID = '{$_SESSION["userID"]}'
AND EventPrimary.eventDate >= CAST('".todayDate."t' AS datetime)
ORDER BY EventPrimary.eventDate ASC"
, "ASSOC");


//Create date variable for yesterday in required YYYY-MM-DD format
$yesterday = date_create(todayDate);
date_sub($yesterday, date_interval_create_from_date_string("1 day"));
$yesterday = date_format($yesterday, "Y-m-d");


//Create date variable for last year in required YYYY-MM-DD format
$lastYear = date_create(todayDate);
date_sub($lastYear, date_interval_create_from_date_string("365 days"));
$lastYear = date_format($lastYear, "Y-m-d");


/* 

THE BELOW SQL QUERY NEEDS TO BE RE-DONE FOR AMBASSADOR EVENT HISTORY 
 
*/


$previousEvents = sqlFetch("SELECT EventRegistration.*, EventPrimary.eventDate, eventName, level, topic, reportLocation
FROM EventRegistration
JOIN EventPrimary USING (eventID)
JOIN EventClass USING (eventID)
JOIN EventAmbassadors USING (eventID)
WHERE EventRegistration.universityID = '{$_SESSION["userID"]}' AND
EventPrimary.eventDate BETWEEN CAST('{$lastYear}' AS datetime) AND CAST('{$yesterday}' AS datetime) 
ORDER BY EventPrimary.eventDate ASC
LIMIT 10"
, "ASSOC");


/*

THE ABOVE SQL QUERY NEEDS TO BE RE-DONE FOR AMBASSADOR EVENT HISTORY

*/

?>

<h4>Upcoming Events</h4>

<?php

if(count($upcomingEvents) > 0){

    echo "
    <table class='ambassador-upcoming-event'>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Report Location</th>
            <th>Event Details</th>
        </tr>";

    foreach($upcomingEvents as $event){
        echo "
        <tr>
            <td>".decrypt($event["eventName"])."</td>
            <td>".$event["eventDate"]."</td>
            <td>".$event["requestedStartTime"]."</td>
            <td>".$event["requestedEndTime"]."</td>
            <td>".$event["reportLocation"]."</td>
            <td>";
            
            if($event["topic"] && $event["level"]){
                echo echoEventTopics(fetchEventTopics($event["topic"]))."<br/>".$event["level"];
            }
            else if($event["topic"]){
                echo echoEventTopics(fetchEventTopics($event["topic"]));
            }
            else if($event["level"]){
                echo $event["level"];
            }

            echo "</td>
        </tr>";
    }

    echo "</table>";
}
else{
    echo "<p>You have no upcoming events</p>";
}

?>

<br/>
<h4>Previous Events</h4>

<?php

if(count($previousEvents) > 0){

    echo "
    <table class='ambassador-upcoming-event'>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Event Details</th>
        </tr>";

    foreach($previousEvents as $event){
        echo "
        <tr>
            <td>".decrypt($event["eventName"])."</td>
            <td>".$event["eventDate"]."</td>
            <td>".$event["requestedStartTime"]."</td>
            <td>".$event["requestedEndTime"]."</td>
            <td>";
            
            if($event["topic"] && $event["level"]){
                echo echoEventTopics(fetchEventTopics($event["topic"]))."<br/>".$event["level"];
            }
            else if($event["topic"]){
                echo echoEventTopics(fetchEventTopics($event["topic"]));
            }
            else if($event["level"]){
                echo $event["level"];
            }

            echo "</td>
        </tr>";
    }

    echo "</table>";
}
else{
    echo "<p>You have no previous events from the last year.</p>";
}

?>

</div><!-- my-events -->


<?php

include "../PageComponents/Foot.php";

?>