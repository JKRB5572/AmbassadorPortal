<?php

include "../PageComponents/Head.php";

checkPageIsActive("EventAmbassadors");

$eventID = $_GET["id"];

$eventPrimary = sqlFetch("SELECT * FROM EventPrimary WHERE eventId = '".$eventID."'", "ASSOC");

if(sizeof($eventPrimary) == 0){
    header("Location: /Admin/Calendar.php");
}

$eventPrimary = $eventPrimary[0]; //Remove outer array

$eventAmbassadors = sqlFetch("SELECT * FROM EventAmbassadors WHERE eventID = '".$eventID."'", "ASSOC");
$eventAmbassadors = $eventAmbassadors[0];

$eventWorkshop = sqlFetch("SELECT level, topic FROM EventWorkshop WHERE eventID = '".$eventID."'", "ASSOC");
$eventWorkshop = $eventWorkshop[0];

$leadAmbassador = sqlFetch("SELECT surname, forename, givenName FROM Ambassadors WHERE universityID = '".$eventAmbassadors["leadAmbassador"]."'", "ASSOC");

if(count($leadAmbassador) > 0){
    $leadAmbassador = returnFullName($leadAmbassador[0]["surname"], $leadAmbassador[0]["forename"], $leadAmbassador[0]["givenName"], true);
}
else{
    $leadAmbassador = "";
}

$registeredAmbassadors = sqlFetch("SELECT Ambassadors.universityID, surname, forename, givenName, programOfStudy, yearOfStudy, trainingCompleted, hoursWorked, recordID, requestedStartTime, requestedEndTime FROM Ambassadors, EventRegistration WHERE Ambassadors.universityID = EventRegistration.universityID AND eventID = '".$eventID."'", "ASSOC");

foreach($registeredAmbassadors as $key => $ambassador){
    $registeredAmbassadors[$key]["forename"] = decrypt($ambassador["forename"]);
    $registeredAmbassadors[$key]["surname"] = decrypt($ambassador["surname"]);
    $registeredAmbassadors[$key]["givenName"] = decrypt($ambassador["givenName"]);
}

usort($registeredAmbassadors, function($a, $b) {
    $returnValue = $a["hoursWorked"] <=> $b["hoursWorked"];
    if($returnValue == 0){
        $returnValue = $a["surname"] <=> $b["surname"];
        if($returnValue == 0){
            $returnValue = $a["forename"] <=> $b["surname"];
        }
    }
    return $returnValue;
});

?>


<script>
var selectedAmbassadors = [];

function addAmbassador(id){
    var ambassador = document.getElementById(id).innerHTML;
    var slots = document.getElementsByClassName("selected-ambassador-slot");

    for(var i = 0; i < slots.length; i++){
        if(slots[i].innerHTML == ""){
            var ambassadorName = document.getElementById(id).innerHTML
            document.getElementById(id).outerHTML = "<div class='empty-ambassador-slot'></div>";
            slots[i].outerHTML = "<div id='" + id + "' class='selected-ambassador-slot' onclick='removeAmbassador(\"" + id + "\")'>" + ambassador + "</div>";
            slots[i].style.backgroundColor = 'lightgreen';

            selectedAmbassadors.push({id, ambassadorName});
            break;
        }
    }
}


function removeAmbassador(id){
    var ambassador = document.getElementById(id);
    var slots = document.getElementsByClassName("empty-ambassador-slot");

    slots[0].outerHTML = "<div id='" + id + "' class='registered-ambassador-slot' onclick='addAmbassador(\"" + id + "\")'>" + ambassador.innerHTML + "</div>";
    ambassador.outerHTML = "<div class='selected-ambassador-slot'></div>";

    for(var i = 0; i < selectedAmbassadors.length; i++){
        if(selectedAmbassadors[i].id == id){
            selectedAmbassadors.splice(i, 1);
            break;
        }
    }
}


function populatePageTwo(){
    var node = document.getElementById("pageTwoContent");
    var tableNode = document.createElement('table');
    var placeholder = 
    `
    <table>
            <tr>
                <th>Ambassador</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
    `;
    
    for(var i = 0; i < selectedAmbassadors.length; i++){
        placeholder +=
        `
        <tr>
            <td id='` + selectedAmbassadors[i].id + `'>` + selectedAmbassadors[i].ambassadorName + `</td>
            <td><input type='time' step=900 value='<?php echo $eventPrimary["startTime"]; ?>'></td>
            <td><input type='time' step=900 value='<?php echo $eventPrimary["endTime"]; ?>'></td>
        </tr>
        `;
    }

    placeholder += "</table>";

    node.appendChild(tableNode);
    tableNode.outerHTML = placeholder;
}


function clearPageTwo(){
    document.getElementById("pageTwoContent").outerHTML = "<div id='pageTwoContent'></div>";
}


function submitSelection(){
    var values = document.getElementsByTagName("td");
    var postHeader = "";
    var i = 0;
    var eventID = "<?php echo $eventID; ?>";

    for(i; i < values.length - 3; i+=3){
        postHeader += values[i].id + "," + values[i+1].childNodes[0].value + "," + values[i+2].childNodes[0].value + ",";
    }
    postHeader += values[i].id + "," + values[i+1].childNodes[0].value + "," + values[i+2].childNodes[0].value;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            changePage('pageTwo', 'pageThree');
        }
    }

    xmlhttp.open("POST", "../PHP/Scripts/SelectEventAmbassadors.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("eventID=" + eventID + "&postArray=" + postHeader);
}

</script>

<div class="event-ambassadors">

    <h2><?php echo decrypt($eventPrimary["eventName"]); ?></h2>

    <h4>Event Summary</h4>

    <?php 

    echo "
    <p>
        <strong>".verboseDateHTML($eventPrimary["eventDate"])."</strong><br/>".
        substr($eventPrimary["startTime"], 0, 5)." - ".substr($eventPrimary["endTime"], 0, 5)."<br/>".
        decrypt($eventPrimary["type"]);

        if(isset($eventWorkshop["level"])){
            echo " - ".$eventWorkshop["level"];
        }

        echo "<br/>".verboseList(fetchTopics($eventWorkshop["topic"]))."<br/>";

        if($eventAmbassadors["trainingRequired"] == "Y"){
            echo "Training is required for this event";
        }
        else if($eventAmbassadors["trainingRequired"] == "P"){
            echo "Training is preferred for this event";
        }
        else if($eventAmbassadors["trainingRequired"] == "N"){
            echo "Training is not required for this event";
        }

        ?>
    </p>

    <div id='pageOne'>

        <div class="two-column-layout-left">
            <h5>Selected Ambassadors</h5>

            <?php

            if(strlen($leadAmbassador) > 7){
                echo "<div class='selected-ambassador-slot'>".$leadAmbassador."</div>";
                $i = 1;
            }
            else{
                $i = 0; 
            }

            for($i; $i < $eventAmbassadors["numberNeeded"]; $i++){
                echo "
                <div class='selected-ambassador-slot'></div>
                ";
            }

            ?>

        </div><!-- two-column-layout-left -->

        <div class="two-column-layout-right">
            <h5>Registered Ambassadors</h5>

            <?php

            foreach($registeredAmbassadors as $ambassador){
                echo "
                <div id='".$ambassador["universityID"]."'
                class='registered-ambassador-slot'
                onclick='addAmbassador(\"".$ambassador["universityID"]."\")'>
                <strong>".returnFullName($ambassador["surname"], $ambassador["forename"], $ambassador["givenName"])."</strong><br/>
                ".verboseYearOfStudy($ambassador["yearOfStudy"])." ".studyProgramName($ambassador["programOfStudy"])."<br/>
                Trained in ".verboseList(fetchTopics($ambassador["trainingCompleted"]))."<br/> 
                ".$ambassador["hoursWorked"]." hours worked
                </div>";

                if($ambassador["requestedStartTime"]){
                    echo "<script>addAmbassador('".$ambassador["universityID"]."')</script>";
                }
            }

            ?>

        </div><!-- two-column-layout-right -->

        <div class="single-column-layout">

            <button onclick='changePage("pageOne", "pageTwo", populatePageTwo)'>Continue</button>
        
        </div><!-- single-column-layout -->

    </div><!-- pageOne -->

    <div id="pageTwo" style="display: none;">
        <button onclick='changePage("pageTwo", "pageOne", clearPageTwo)'>Go Back</button>
        <div id='pageTwoContent'></div>
        <button onclick='submitSelection()'>Submit</button>
    </div><!-- pageTwo -->

    <div id="pageThree" style="display: none;">
        <p>Selection Submitted</p>
    </div><!-- pageThree -->

</div><!-- event-ambassadors -->

<?php

include "../PageComponents/Foot.php";

?>

