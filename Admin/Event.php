<?php

include "../PageComponents/Head.php";
require "/var/www/html/PHP/EditEventFunctions.php";


function visibilityClass($query, $value){
    if($query == $value){
        echo "class='active'";
    }
}


checkPageIsActive("Event");
$editAllowed = checkPageIsActive("EventEdit", True);

if(isset($_GET["id"])){

    $eventID = $_GET["id"];

    if($_SERVER["REQUEST_METHOD"] === "POST"){

        $url = "http://".$_SERVER["HTTP_HOST"]."/PHP/Scripts/DeleteEvent.php";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "id=".$eventID);
        curl_exec($ch);
        curl_close($ch);

    }


    $eventPrimary = sqlFetch("SELECT * FROM EventPrimary WHERE eventID = ".$eventID, "ASSOC");
    
    if(count($eventPrimary) == 0){
        header("Location: Calendar.php");
    }
    else{
        $eventPrimary = $eventPrimary[0]; //Remove outer array
        
        $eventLocation = sqlFetch("SELECT * FROM EventLocation WHERE eventID = ".$eventID, "ASSOC");
        $eventLocation = $eventLocation[0];

        $eventClass = sqlFetch("SELECT * FROM EventClass WHERE eventID = ".$eventID, "ASSOC");
        $eventClass = $eventClass[0];

        $eventAmbassadors = sqlFetch("SELECT * FROM EventAmbassadors WHERE eventID = ".$eventID, "ASSOC");
        $eventAmbassadors = $eventAmbassadors[0];

        $eventContact = sqlFetch("SELECT * FROM EventContact WHERE eventID = ".$eventID, "ASSOC");
        $eventContact = $eventContact[0];

        $eventAdditionalInformation = sqlFetch("SELECT * FROM EventAdditionalInformation WHERE eventID = ".$eventID, "ASSOC");
        $eventAdditionalInformation = $eventAdditionalInformation[0];

        $informationComplete = checkEventCompleteness($eventPrimary, $eventLocation, $eventClass, $eventAmbassadors, $eventContact);


        $eventTopic = echoEventTopics(fetchEventTopics($eventClass["topic"]), True);

        $leadAmbassador = fetchName($eventAmbassadors["leadAmbassador"], "Admin");
        if(strlen($leadAmbassador) == 1){
            $leadAmbassador = null;
        }

        $selectedAmbassadors = sqlFetch("SELECT EventRegistration.universityID, Ambassadors.surname, Ambassadors.forename, Ambassadors.givenName FROM EventRegistration JOIN Ambassadors USING (universityID) WHERE requestedStartTime IS NOT NULL AND eventID = '".$eventID."'", "ASSOC");
    
        if($editAllowed == True){
            ?>

            <script src="/JavaScript/Scripts/UpdateEventVisibility.js"></script>
            <script>

            function verifyDelete(){
                var confirmDelete = confirm("Are you sure you wish to delete this event. This cannot be undone.");
                if(confirmDelete == true){
                    document.getElementById("deleteEvent").submit();
                }
            }

            </script>

            <form method="POST" id="deleteEvent"></form>

            <?php
        }
        ?>

        <div class="event">

            <div class="view-event-title">
                <h2 ondblclick="redirect('/Admin/EventEdit.php?id=<?php echo $eventID; ?>&sectionToEdit=primary')"><?php echo decrypt($eventPrimary["eventName"]); ?></h2>
            </div>

            <?php

            if($editAllowed == True){
                ?> 

                <div class="event-toolbar">
                    <p>Double click on a section to edit it.</p>
                    <ul>
                        <li id="eventDelete" onclick="verifyDelete();"><i class="fa fa-trash"></i></li>
                        <li id="eventShowAll" <?php visibilityClass($eventPrimary["visibility"], 0); ?> onclick="updateVisibility('<?php echo $eventID; ?>', '0')"><i class="fa fa-users"></i></li>
                        <li id="eventShowAdmin" <?php visibilityClass($eventPrimary["visibility"], 1); ?> onclick="updateVisibility('<?php echo $eventID; ?>', '1')"><i class="fa fa-user"></i></li>
                        <li id="eventHide" <?php visibilityClass($eventPrimary["visibility"], 2); ?> onclick="updateVisibility('<?php echo $eventID; ?>', '2')"><i class="fa fa-eye-slash"></i></li>
                    </ul>
                </div>

                <?php
            }
            ?>


            <div class="three-column-layout-row">
                <div class="three-column-layout-left">

                    <div id="primary" ondblclick="redirect('/Admin/EventEdit.php?id=<?php echo $eventID; ?>&sectionToEdit=primary')">

                        <h4>Basic Information</h4>

                        <table>

                            <?php

                            echoTableRowRequired($eventPrimary["fundingSource"], "Funding Source", True);
                            echoTableRow($eventPrimary["project"], "Project", True);
                            echoTableRowRequired(verboseDateHTML($eventPrimary["eventDate"]), "Date");
                            echoTableRowRequired(substr($eventPrimary["startTime"], 0, 5), "Start Time");
                            echoTableRowRequired(substr($eventPrimary["endTime"], 0, 5), "End Time");
                            echoTableRowRequired($eventPrimary["type"], "Event Type", True);

                            ?>

                        </table>

                    </div>

                </div><!-- three-column-layout-left -->
                <div class="three-column-layout-middle">

                    <div id="location" ondblclick="redirect('/Admin/EventEdit.php?id=<?php echo $eventID; ?>&sectionToEdit=location')">

                        <h4>Location</h4>

                        <table>

                            <?php

                            echoTableRowRequired($eventLocation["address1"], "Address Line 1", True);
                            echoTableRow($eventLocation["address2"], "Address Line 2", True);
                            echoTableRow($eventLocation["county"], "County", True);
                            echoTableRowRequired($eventLocation["postcode"], "Postcode", True);
                            echoTableRowRequired($eventLocation["transport"], "Transport");

                            if($eventLocation["transport"] != "None"){
                                if($eventLocation["transportBooked"] == 0){
                                    echoTableRow("No", "Transport Booked");
                                }
                                elseif($eventLocation["transportBooked"] == 0){
                                    echoTableRow($eventLocation["transportBooked"], "Transport Booked");
                                }
                                else{
                                    echoTableRow("NA", "DATABASE ERROR");
                                }
                            }

                            ?>

                        </table>

                    </div>

                </div>
                <div class="three-column-layout-right">

                    <div id="class" ondblclick="redirect('/Admin/EventEdit.php?id=<?php echo $eventID; ?>&sectionToEdit=class')">

                        <h4>Class Details</h4>

                        <table>

                            <?php

                            echoTableRow($eventClass["className"], "Class Name", True);
                            echoTableRow($eventClass["classSize"], "Class Size");

                            if(isCardiffEvent($eventPrimary["type"]) == true){
                                echoTableRow($eventClass["level"], "Level");
                            }
                            else{
                                echoTableRowRequired($eventClass["level"], "Level");
                            }

                            if(isCardiffEvent($eventPrimary["type"]) == true){
                                echoTableRow($eventTopic, "Topic(s)");
                            }
                            else{
                                echoTableRowRequired($eventTopic, "Topic(s)");
                            }

                            ?>

                        </table>

                    </div>

                </div><!-- three-column-layout-right -->
            </div><!-- three-column-layout-row -->

            <div class="three-column-layout-row">
                <div class="three-column-layout-left">

                    <div id="ambassadors" ondblclick="redirect('/Admin/EventEdit.php?id=<?php echo $eventID; ?>&sectionToEdit=ambassadors')">
                    
                        <h4>Ambassadors</h4>

                        <table>

                            <?php

                            echoTableRowRequired($eventAmbassadors["numberNeeded"], "Number Needed");
                            if($eventAmbassadors["trainingRequired"] == "N"){
                                echoTableRow("No", "Training Required");
                            }
                            elseif($eventAmbassadors["trainingRequired"] == "Y"){
                                echoTableRow("Yes", "Training Required");
                            }
                            elseif($eventAmbassadors["traningRequired"] == "P"){
                                echoTableRow("Preferred", "TrainingRequired");
                            }
                            else{
                                echoTableRow(null, "Training Required");
                            }
                            echoTableRow($leadAmbassador, "Lead Ambassador");
                            echoTableRowRequired($eventAmbassadors["reportLocation"], "Report Location");

                            ?>

                        </table>

                    </div>

                </div><!-- three-column-layout-left -->
                <div class="three-column-layout-middle">

                    <div id="contact" ondblclick="redirect('/Admin/EventEdit.php?id=<?php echo $eventID; ?>&sectionToEdit=contact')">

                        <h4>Contact Details</h4>

                        <table>

                            <?php

                            echoTableRow($eventContact["name"], "Name", True);
                            echoTableRow($eventContact["email"], "Email", True);
                            echoTableRow($eventContact["phoneNo"], "Phone Number", True);

                            ?>

                        </table>

                    </div>

                </div><!-- three-column-layout-middle --> 
                <div class="three-column-layout-right">

                    <div id="additionalInformation" ondblclick="redirect('/Admin/EventEdit.php?id=<?php echo $eventID; ?>&sectionToEdit=additionalInformation')">

                        <h4>Additional Information</h4>

                        <table>

                            <?php

                            echoTableRow($eventAdditionalInformation["resourcesRequired"], "Resources Required", True);
                            echoTableRow($eventAdditionalInformation["additionalInformation"], "Additional Information", True);

                            ?>

                        </table>

                    </div>

                </div><!-- three-column-layout-right -->
            </div><!-- three-column-layout-row -->
            <div class="three-column-layout-row">
                <div class="three-column-layout-left">

                    <div id="eventAmbassadors" ondblclick="redirect('/Admin/EventAmbassadors.php?id=<?php echo $eventID; ?>')">

                        <h4>Selected Ambassadors</h4>

                        <p>

                        <?php 

                        if(count($selectedAmbassadors) > 0){
                            foreach($selectedAmbassadors as $ambassador){
                                echo returnFullName($ambassador["surname"], $ambassador["forename"], $ambassador["givenName"], True) ."<br/>";
                            }
                        }
                        else{
                            echo "Ambassadors have yet to be selected for this event";
                        }

                        ?>

                        </p>

                    </div>
                
                </div><!-- three-column-layout-left -->
            </div><!-- three-column-layout -->
                    

                        

        </div><!-- event -->

    <script>
    var informationComplete = <?php echo $informationComplete ? 'true' : 'false'; ?>;

    if(informationComplete == true){
        document.getElementById("eventShowAll").style.display = "block";
        document.getElementById("eventShowAdmin").style.display = "block";
        document.getElementById("eventHide").style.borderRadius = "10px 0px 0px 10px";
    }
    else if(informationComplete == true){
    }

    </script>

    

    <?php

    }
}
else{
    header("Location: Calendar.php");
}

?>

<?php

include "../PageComponents/Foot.php";

?>