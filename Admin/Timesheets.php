<?php

require_once "/var/www/html/PageComponents/Head.php";

checkPageIsActive("EventAmbassadors");

$eventID = $_GET["id"];

$eventPrimary = sqlFetch("SELECT * FROM EventPrimary WHERE eventID = '".$eventID."'", "ASSOC");

if(sizeof($eventPrimary) == 0){
    header("Location: /Admin/Calendar.php");
}

$eventPrimary = $eventPrimary[0];

$eventWorkshop = sqlFetch("SELECT level, topic FROM EventWorkshop WHERE eventID = '".$eventID."'", "ASSOC");
$eventWorkshop = $eventWorkshop[0];


$registeredAmbassadors = sqlFetch(
    "SELECT Ambassadors.universityID, surname, forename, givenName, hoursWorked, recordID, requestedStartTime, requestedEndTime
    FROM Ambassadors, EventRegistration
    WHERE Ambassadors.universityID = EventRegistration.universityID
    AND eventID = '".$eventID."' 
    AND requestedStartTime IS NOT NULL"
,"ASSOC");


?>

<script>

function submitSelection(){
    var values = document.getElementsByTagName("td");
    var postHeader = "";
    var i = 0;
    var eventID = "<?php echo $eventID; ?>";

    for(i; i < values.length - 4; i+=4){
        postHeader += values[i].id + "," + values[i+1].childNodes[0].checked + "," + values[i+2].childNodes[0].value + "," + values[i+3].childNodes[0].value + ","
    }
    postHeader += values[i].id + "," + values[i+1].childNodes[0].checked + "," + values[i+2].childNodes[0].value + "," + values[i+3].childNodes[0].value;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            console.log(this.responseText);
        }
    }

    xmlhttp.open("POST", "../PHP/Scripts/LogTimesheets.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("eventID=" + eventID + "&postArray=" + postHeader);
}

</script>


<h2><?php echo decrypt($eventPrimary["eventName"]); ?></h2>

<h4>Event Summary</h4>

<p>Please note that currently there is no way to edit these timesheets once they have been submitted. Therefore please ensure all information is correct before submitting</p>

<?php 

echo "
<p>
    <strong>".verboseDateHTML($eventPrimary["eventDate"])."</strong><br/>".
    substr($eventPrimary["startTime"], 0, 5)." - ".substr($eventPrimary["endTime"], 0, 5)."<br/>".
    decrypt($eventPrimary["type"]);

    if(isset($eventWorkshop["level"])){
        echo " - ".$eventWorkshop["level"];
    }

    echo "<br/>".verboseList(fetchTopics($eventWorkshop["topic"]))."<br/>
</p>";

?>

<h4>Timesheets</h4>

    <table>
        <tr>
            <th>Ambassador</th>
            <th>Confirm Attendence</th>
            <th>Confirm Start Time</th>
            <th>Confirm End Time</th>
        </tr>

        <?php

        foreach($registeredAmbassadors as $ambassador){
            echo "
            <tr>
                <td id='".$ambassador["universityID"]."'>".returnFullName($ambassador["surname"], $ambassador["forename"], $ambassador["givenName"], True)."</td>
                <td><input type='checkbox'></td>
                <td><input type='time' value='".substr($ambassador["requestedStartTime"], 0, 5)."' step='900'></td>
                <td><input type='time' value='".substr($ambassador["requestedEndTime"], 0, 5)."' step='900'></td>
            </tr>";
        }

        ?>

    </table>

    <button class="server-action" onclick="submitSelection()">Submit