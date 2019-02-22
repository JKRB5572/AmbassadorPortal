<?php

include "../PageComponents/Head.php";
include "../PHP/Scripts/AddEventManageSubmission.php";
require "/var/www/html/PHP/EditEventFunctions.php";
require "/var/www/html/Admin/EventSections/Subsections.php";

checkPageIsActive("AddEvent");

?>

<script src="../JavaScript/AddEvent.js"></script>

<h2>Add Event - <span id='sectionName'>Basic Details</span></h2>

<div class="event-form">

    <div class="add-event-progress-bar">
        <i id="section1" class="fa fa-circle-thin" onclick="changePage(getActiveSection('string'), 'primary', updatePage)"></i>
        <div id="connector1"></div>
        <i id="section2" class="fa fa-circle-thin" onclick="changePage(getActiveSection('string'), 'location', updatePage)"></i>
        <div id="connector2"></div>
        <i id="section3" class="fa fa-circle-thin" onclick="changePage(getActiveSection('string'), 'class', updatePage)"></i>
        <div id="connector3"></div>
        <i id="section4" class="fa fa-circle-thin" onclick="changePage(getActiveSection('string'), 'ambassadors', updatePage)"></i>
        <div id="connector4"></div>
        <i id="section5" class="fa fa-circle-thin" onclick="changePage(getActiveSection('string'), 'contact', updatePage)"></i>
        <div id="connector5"></div>
        <i id="section6" class="fa fa-circle-thin" onclick="changePage(getActiveSection('string'), 'equipment', updatePage)"></i>
        <div id="connector6"></div>
        <i id="section7" class="fa fa-circle-thin" onclick="changePage(getActiveSection('string'), 'additionalInformation', updatePage)"></i>
    </div>

    <div id="errorOutput"></div>

    <form id="addEventForm" method="POST" onkeypress="return event.keycode != 13;" style="width: 600px; margin: 0 auto;">

        <div id="primary">

            <?php include "/var/www/html/Admin/EventSections/Primary.php"; ?>

            <div class="previous-next"  style="width: 55.83px;">
                <button class="client-action" type="button" onclick="checkNoErrors('primary', 'location')">Next</button>
            </div>

        </div><!-- primary -->
        

        <div id="location" style="display: none;">

            <?php include "/var/www/html/Admin/EventSections/Location.php"; ?>

            <div class="previous-next">
                <button class="client-action" type="button" onclick="checkNoErrors('location', 'primary')" style="float: left;">Previous</button>
                <button class="client-action" type="button" onclick="checkNoErrors('location', 'class')" style="float: right;">Next</button>
            </div>

        </div><!-- location -->

        <div id="class" style="display: none;">

            <?php include "/var/www/html/Admin/EventSections/Class.php"; ?>

            <div class="previous-next">
                <button class="client-action" type="button" onclick="checkNoErrors('class', 'location')" style="float: left;">Previous</button>
                <button class="client-action" type="button" onclick="checkNoErrors('class', 'ambassadors')" style="float: right;">Next</button>
            </div>

        </div><!-- class -->

        <div id="ambassadors" style="display: none;">

            <?php include "/var/www/html/Admin/EventSections/Ambassadors.php"; ?>

            <div class="previous-next">
                <button class="client-action" type="button" onclick="checkNoErrors('ambassadors', 'class')">Previous</button>
                <button class="client-action" type="button" onclick="checkNoErrors('ambassadors', 'contact')">Next</button>
            </div>

        </div><!-- ambassadors -->

        <div id="contact" style="display: none;">

            <?php include "/var/www/html/Admin/EventSections/Contact.php"; ?>

            <div class="previous-next">
                <button class="client-action" type="button" onclick="checkNoErrors('contact', 'ambassadors')">Previous</button>
                <button class="client-action" type="button" onclick="checkNoErrors('contact', 'equipment')">Next</button>
            </div>

        </div><!-- contact -->

        <div id="equipment" style="display: none;">

            <p><em>Feature yet to be added</em></p>

            <div class="previous-next">
                <button class="client-action" type="button" onclick="checkNoErrors('equipment', 'contact')" style="float: left;">Previous</button>
                <button class="client-action" type="button" onclick="checkNoErrors('equipment', 'additionalInformation')" style="float: right;">Next</button>
            </div>
            
        </div><!-- equipment -->

        <div id="additionalInformation" style="display: none;">

            <?php include "/var/www/html/Admin/EventSections/AdditionalInformation.php"; ?>

            <div class="previous-next" style="width: 82.26px;">
                <button class="client-action" type="button" onclick="checkNoErrors('additionalInformation', 'equipment')">Previous</button>
            </div>

        </div><!-- additionalInformation -->

        <div class="single-column-layout" style="width: 73.6px; margin: 0 auto;">
            <button id="formSubmission" class="server-action" type="button" onclick="validateSubmit()" style="background-color: gray;">Submit</button>
        </div><!-- single-column-layout -->

    </form><!-- addEventForm -->

</div><!-- event-form -->
            
<script>
//Define required variable phpFormPath for validateSubmit function.
var phpFormPath = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>";

//De-select all selection boxes.
document.getElementById('fundingSource').selectedIndex = -1;
document.getElementById('eventType').selectedIndex = -1;
document.getElementById('leadAmbassador').selectedIndex = -1;
showNumberOfRepeats();
</script>

<?php

include "../PageComponents/Foot.php";

?>
