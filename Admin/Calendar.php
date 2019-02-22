<?php

include "../PageComponents/Head.php";

checkPageIsActive("CalendarAdmin");

?>

<script src="../JavaScript/Calendar.js"></script>
<script src="../JavaScript/CalendarAdmin.js"></script>

<script>

var today = new Date();
var calendarMonth = today.getMonth();
var calendarYear = today.getFullYear();
var todayMonth = calendarMonth;
var todayYear = calendarYear;
var todayDay = today.getDate();
var eventDisplay = "<?php if(isset($_SESSION["eventDisplay"])){ echo htmlspecialchars_decode($_SESSION["eventDisplay"]); }else{ echo "null"; } ?>";


</script>

<div style="float: left; width: 23%;">

    <div class="event-filter-pane">

        <h4>Filter Events</h4>
        <p>Topic</p>
        <table class="collapsed">
            <tr>
                <td><input id="filterGreenfootCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterGreenfoot">Greenfoot</td>
                <td><input id="filterLegoMindstormCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterLegoMindstorms">LEGO Mindstorms</td>
            </tr>
            <tr>
                <td><input id="filterMicrobitsCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterMicrobits">Micro:bits</td>
                <td><input id="filterPythonCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filter">Python</td>
            </tr>
            <tr>
                <td><input id="filterRaspberryPisCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterRaspberryPis">Raspberry Pis</td>
                <td><input id="filterScratchCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterScratch">Scratch</td>
            </tr>
            <tr>
                <td><input id="filterWebDesignCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterWebDesign">Web Design</td>
                <td><input id="filterOtherCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterTopicOther">Other</td>
            </tr>
        </table>
        <br/>
        
        <p>Type</p>
        <table class="collapsed">
            <tr>
                <td><input id="filterPrimarySchoolCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterPrimarySchool">Primary School</td>
                <td><input id="filterSecondarySchoolCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterSecondarySchool">Secondary School</td>
            </tr>
            <tr>
                <td><input id="filterCollegeCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterCollege">College</td>
                <td><input id="filterCPDCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterCPD">CPD</td>
            </tr>
            <tr>
                <td><input id="filterCommunityCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterCommunity">Community</td>
                <td><input id="filterOpenDay" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterOpenDay">Open Day</td>
            </tr>
            <tr>
                <td><input id="filterUCASDayCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterUCASDay">UCAS Day</td>
                <td><input id="filterNetworkingEventCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterNetworkingEvent">Networking</td>
            </tr>
            <tr>
                <td><input id="filterTypeOtherCheckbox" type="checkbox" onclick="filterTopic()"></td>
                <td id="filterTypeOther">Other</td>
            </tr>
        </table>

    </div><!-- event-filter-pane -->

    

    <div class="event-details-pane">
        <h4>Event Details</h4>
        <p id="showEventDetails">No event selected</p>
    </div><!-- event-details-pane -->

</div><!-- float: left -->

<div style="float: right; width: 75%; padding-bottom: 20px;">

    <div class="calendar-header">
        <div>
            <ul>
                <li><a href="AddEvent.php"><i class="fa fa-calendar-plus-o"></i></a></li>
            </ul>
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

    <div id="listView" class="list-view" style="display: hidden;"></div><!-- #listView -->

</div><!-- float: right; -->

<div id="loadingMoreSpinner" style="display: hidden:"><img src="../PageComponents/Images/loading-spinner.gif"></div>

<script>
populateCalendar(calendarMonth, calendarYear);
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