<?php

include "../PageComponents/Head.php";


$myEvents = sqlFetch("SELECT eventID, requestedStartTime FROM EventRegistration WHERE universityID = '".$_SESSION["userID"]."'", "ASSOC");

$otherEvents = sqlFetch("SELECT DISTINCT eventID FROM EventRegistration WHERE requestedStartTime IS NOT NULL AND universityID <> '".$_SESSION["userID"]."'", "ASSOC");

$otherEventsFormatted = array();

foreach($otherEvents as $event){
    array_push($otherEventsFormatted, $event["eventID"]);
}

$workingEvents = array();       //Events an ambassador is scheduled to work
$registeredEvents = array();    //Events an ambassador has registered interest in working
$reserveEvents = array();       //Events where ambassadors are on reserve

foreach($myEvents as $event){
    if($event["requestedStartTime"]){
        array_push($workingEvents, $event["eventID"]);
    }
    else{
        if(!in_array($event["eventID"], $otherEventsFormatted)){
            array_push($registeredEvents, $event["eventID"]);
        }
        else{
            array_push($reserveEvents, $event["eventID"]);
        }
    }
}

?>

<script src="../JavaScript/Calendar.js"></script>
<script src="../JavaScript/CalendarAmbassador.js"></script>

<script>

var today = new Date();
var calendarMonth = today.getMonth();
var calendarYear = today.getFullYear();
var todayMonth = calendarMonth;
var todayYear = calendarYear;
var todayDay = today.getDate();
var eventDisplay = "<?php if(isset($_SESSION["eventDisplay"])){ echo htmlspecialchars_decode($_SESSION["eventDisplay"]); }else{ echo "null"; } ?>";
var userID = "<?php echo $_SESSION["userID"]; ?>";
var training = <?php echo json_encode($_SESSION["training"]); ?>;
if(training){
    training = training.map(Number);
}
var workingEvents = <?php echo json_encode($workingEvents); ?>;
var registeredEvents = <?php echo json_encode($registeredEvents); ?>;
var reserveEvents = <?php echo json_encode($reserveEvents); ?>;

</script>

<div style="float: left; width: 23%;">

    <div class="event-details-pane">
        <h4>Event Details</h4>
        <p id="showEventDetails">No event selected</p>
    </div><!-- event-details-pane -->

</div><!-- float: left -->

<div style="float: right; width: 75%;">

    <div class="calendar-header">
        <div>
        </div>
        <div>
            <h2 class='marginlessHeader'><i class="fa fa-angle-left" onClick="previousMonth()" style="color: black;"></i>&nbsp;&nbsp;<span id="calendarDate"></span>&nbsp;&nbsp;<i class="fa fa-angle-right" onClick="nextMonth()" style="color: black;"></i></h2>
        </div>
        <div>
            <ul>
                <li id="listButton" onclick="changeDisplay('listButton')" style="float:right"><a><i class="fa fa-list"></i></a></li>
                <li id="calendarButton" onclick="changeDisplay('calendarButton')" style="float:right"><a><i class="fa fa-calendar"></i></a></li>
            </ul>
        </div>
    </div><!-- calendar-header -->

    <div id="calendarView">
        <table class="calendar" id="calendarHead">
            <tr>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
                <th>Sunday</th>
                <th style="width: 5px;"></th>
            </tr>
        </table>

        <div class="calendar-view">
            <table class="calendar" id="calendarBody">
                <tbody>
                    <tr>
                        <td id="calCell0"></td>
                        <td id="calCell1"></td>
                        <td id="calCell2"></td>
                        <td id="calCell3"></td>
                        <td id="calCell4"></td>
                        <td id="calCell5"></td>
                        <td id="calCell6"></td>
                    </tr>
                    <tr>
                        <td id="calCell7"></td>
                        <td id="calCell8"></td>
                        <td id="calCell9"></td>
                        <td id="calCell10"></td>
                        <td id="calCell11"></td>
                        <td id="calCell12"></td>
                        <td id="calCell13"></td>
                    </tr>
                    <tr>
                        <td id="calCell14"></td>
                        <td id="calCell15"></td>
                        <td id="calCell16"></td>
                        <td id="calCell17"></td>
                        <td id="calCell18"></td>
                        <td id="calCell19"></td>
                        <td id="calCell20"></td>
                    </tr>
                        <td id="calCell21"></td>
                        <td id="calCell22"></td>
                        <td id="calCell23"></td>
                        <td id="calCell24"></td>
                        <td id="calCell25"></td>
                        <td id="calCell26"></td>
                        <td id="calCell27"></td>
                    </tr>
                    </tr>
                        <td id="calCell28"></td>
                        <td id="calCell29"></td>
                        <td id="calCell30"></td>
                        <td id="calCell31"></td>
                        <td id="calCell32"></td>
                        <td id="calCell33"></td>
                        <td id="calCell34"></td>
                    </tr>
                    </tr>
                        <td id="calCell35"></td>
                        <td id="calCell36"></td>
                        <td id="calCell37"></td>
                        <td id="calCell38"></td>
                        <td id="calCell39"></td>
                        <td id="calCell40"></td>
                        <td id="calCell41"></td>
                    </tr>
                </tbody>
            </table>
        </div><!-- calendar-view -->

    </div><!-- #calendarView -->

    <div id="listView" class="list-view" style="display: none;"></div><!-- #listView -->

</div><!-- float: right; -->

<div id="loadingMoreSpinner" style="display: none;"><img src="../PageComponents/Images/loading-spinner.gif"></div>
<div id="sendingDataSpinner" style="display: none;"><img src="../PageComponents/Images/sending-data-spinner.gif"></div>

<script>
populateCalendar(calendarMonth, calendarYear, true);
<?php

if(isset($_SESSION["eventDisplay"]) && $_SESSION["eventDisplay"] == "list"){
    echo "eventDisplay = null; changeDisplay('listButton');";
}
else{
    echo "eventDisplay = null; changeDisplay('calendarButton');";
}

?>

</script>

<?php

include "../PageComponents/Foot.php";

?>