<?php

include "/var/www/html/PageComponents/Head.php";
require "/var/www/html/PHP/EditEventFunctions.php";
require "/var/www/html/Admin/EventSections/Subsections.php";

checkPageIsActive("EventEdit");

?>

<script src="/JavaScript/AddEvent.js"></script>
<script>

function checkSubmission(){
    var errors = checkForErrors();
    if(errors.numberOfErrors === 0){
        document.getElementById("editEventForm").submit();
    }
    else{
        document.getElementById("errorOutput").innerHTML = printErrorMessages(errors);
    }
}

</script>

<?php

if(!isset($_GET["id"]) && !isset($_GET["sectionToEdit"])){
    header("location: ../Calendar.php");
}
else{
    $eventID = validateInput($_GET["id"]);
    $sectionToEdit = validateInput($_GET["sectionToEdit"]);

    $eventName = sqlFetch("SELECT eventName FROM EventPrimary WHERE eventID = '".$eventID."'", "NUM");
    $eventName = decrypt($eventName[0][0]);

    $isEdit = True;

    ?>
    
    <a href='/Admin/Event.php?id=<?php echo $eventID; ?>'><i class="fa fa-long-arrow-left" style="font-size: 36px;"></i></a>
    <form id="editEventForm" method="POST" style="width: 600px; margin: 0 auto;">

    <?php

    
    if($sectionToEdit == "primary"){
        $table = "EventPrimary";
        $eventDetails = getDetails();
        
        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $eventName = encrypt(validateInput($_POST["eventName"]));
            $project = encrypt(validateInput($_POST["project"]));
            $fundingSource = encrypt(validateInput($_POST["fundingSource"]));
            $eventDate = validateInput($_POST["eventDate"]);
            $startTime = validateInput($_POST["startTime"]);
            $endTime = validateInput($_POST["endTime"]);
            $eventType = encrypt(validateInput($_POST["eventType"]));
        
            if(sqlUpdate("UPDATE EventPrimary SET eventName='{$eventName}', project=".hasValue($project).", fundingSource='{$fundingSource}', eventDate=".hasValue($eventDate).", startTime=".hasValue($startTime).", endTime=".hasValue($endTime).", type='{$eventType}' WHERE eventID = '".$eventID."'", True, True) == True){
                header("location: /Admin/Event.php?id=".$eventID);
            }
            else{
                echo "<p style='color: red;'><strong>A system error has occured: Could not write to EventPrimary table.<br/>Please seek help from the system adminstrator.</strong></p>";
            }
        
        }

        echo "<div class='event-form'>
        <h2>Edit Event <em>".$eventName."</em> - Basic Details</h2>
        <div id='errorOutput'></div>";
        require "/var/www/html/Admin/EventSections/Primary.php";
        echo "</div><!-- event-form -->";

    }

    elseif($sectionToEdit == "location"){
        $table = "EventLocation";
        $eventDetails = getDetails();
        
        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $address1 = encrypt(validateInput($_POST["address1"]));
            $address2 = encrypt(validateInput($_POST["address2"]));
            $county = encrypt(validateInput($_POST["county"]));
            $postcode = encrypt(validateInput($_POST["postcode"]));
            $transport = validateInput($_POST["transport"]);
        
            if(sqlUpdate("UPDATE EventLocation SET address1='{$address1}', address2=".hasValue($address2).", county=".hasValue($county).", postcode=".hasValue($postcode).", transport='{$transport}' WHERE eventID = '{$eventID}'", True, True) == True){
                header("location: /Admin/Event.php?id=".$eventID);
            }
            else{
                echo "<p style='color: red;'><strong>A system error has occured: Could not write to EventLocation table.<br/>Please seek help from the system adminstrator.</strong></p>";
            }
        
        }

        echo "<div class='event-form'>
        <h2>Edit Event </em>".$eventName." - Location</h2>";
        require "/var/www/html/Admin/EventSections/Location.php";
        echo "</div><!-- event-form -->";

    }

    elseif($sectionToEdit == "class"){
        $table = "EventClass";
        $eventDetails = getDetails();
        

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $className = encrypt(validateInput($_POST["className"]));
            $classSize = validateInput($_POST["classSize"]);
            $level = validateInput($_POST["level"]);
            $eventTopic = $_POST["eventTopic"];

            #Format event topic
            if($eventTopic){
                $array = array();

                foreach($eventTopic as $topic){
                    $array[] = (validateInput($topic));
                }

                if($eventTopicOther){
                    $array[count($array) - 1] = $eventTopicOther;
                }

                $eventTopic = json_encode($array);
            }

            if(sqlUpdate("UPDATE EventClass SET className=".hasValue($className).", classSize=".hasValue($classSize).", level=".hasValue($level).", topic=".hasValue($eventTopic)." WHERE eventID='{$eventID}'", True, True) == True){
                header("location: /Admin/Event.php?id=".$eventID);
            }
            else{
                echo "<p style='color: red;'><strong>A system error has occured: Could not write to EventClass table.<br/>Please seek help from the system adminstrator.</strong></p>";
            }
        }

        ?>
        
        <script src='/JavaScript/AddEvent.js'></script>
        <?php
        echo "<div class='event-form'>
        <h2>Edit Event <em>".$eventName."</em> - Class Details</h2>";
        require "/var/www/html/Admin/EventSections/Class.php";
        echo "</div><!-- event-form -->";
        ?>
        <script>populateLevelOptions("<?php
            
            $eventType = sqlFetch("SELECT type FROM EventPrimary WHERE eventID = '".$eventID."'", "NUM");
            echo decrypt($eventType[0][0]); 
            
        ?>");</script>
        <?php

    }

    elseif($sectionToEdit == "ambassadors"){
        $table = "EventAmbassadors";
        $eventDetails = getDetails();

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $numberNeeded = validateInput($_POST["numberNeeded"]);
            $trainingRequired = validateInput($_POST["trainingRequired"]);
            $leadAmbassador = validateInput($_POST["leadAmbassador"]);
            $reportLocation = validateInput($_POST["reportLocation"]);

            if(sqlUpdate("UPDATE EventAmbassadors SET numberNeeded=".hasValue($numberNeeded).", trainingRequired='{$trainingRequired}', leadAmbassador=".hasValue($leadAmbassador).", reportLocation='{$reportLocation}' WHERE eventID='{$eventID}'", True, True) == True){
                header("location: /Admin/Event.php?id=".$eventID);
            }
            else{
                echo "<p style='color: red;'><strong>A system error has occured: Could not write to EventAmbassadors table.<br/>Please seek help from the system adminstrator.</strong></p>";
                sqlUpdate("UPDATE EventAmbassadors SET numberNeeded=".hasValue($numberNeeded).", trainingRequired='{$trainingRequired}', leadAmbassador=".hasValue($leadAmbassador).", reportLocation='{$reportLocation}' WHERE eventID='{$eventID}'", True);
            }

        }

        echo "<div class='event-form'>
        <h2>Edit Event <em>".$eventName."</em> - Ambassadors</h2>";
        require "/var/www/html/Admin/EventSections/Ambassadors.php";
        echo "</div><!-- event-form -->";
        

    }

    elseif($sectionToEdit == "contact"){
        $table = "EventContact";
        $eventDetails = getDetails();

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $contactName = encrypt(validateInput($_POST["contactName"]));
            $contactEmail = encrypt(validateInput($_POST["contactEmail"]));
            $contactPhoneNo = encrypt(validateInput($_POST["contactPhoneNo"]));

            if(sqlUpdate("UPDATE EventContact SET name=".hasValue($contactName).", email=".hasValue($contactEmail).", phoneNo=".hasValue($contactPhoneNo)." WHERE eventID='{$eventID}'", True, True) == True){
                header("location: /Admin/Event.php?id=".$eventID);
            }
            else{
                echo "<p style='color: red;'><strong>A system error has occured: Could not write to EventContact table.<br/>Please seek help from the system adminstrator.</strong></p>";
            }

        }

        echo "<div class='event-form'>
        <h2>Edit Event <em>".$eventName."</em> - Contact Details</h2>";
        require "/var/www/html/Admin/EventSections/Contact.php";
        echo "</div><!-- event-form -->";

    }

    elseif($sectionToEdit == "equipment"){
        $table = "EventEquipment";
        $eventDetails = getDetails();
        

    }

    elseif($sectionToEdit == "additionalInformation"){
        $table = "EventAdditionalInformation";
        $eventDetails = getDetails();

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $resourcesRequired = encrypt(validateInput($_POST["resourcesRequired"]));
            $additionalInformation = encrypt(validateInput($_POST["additionalInformation"]));

            if(sqlUpdate("UPDATE EventAdditionalInformation SET resourcesRequired=".hasValue($resourcesRequired).", additionalInformaion=".hasValue($additionalInformation), True, True) == True){
                header("location: /Admin/Event.php?id=".$eventID);
            }
            else{
                echo "<p style='color: red;'><strong>A system error has occured: Could not write to EventAdditionalInformation table.<br/>Please seek help from the system adminstrator.</strong></p>";
            }

        }

        echo "<div class='event-form'>
        <h2>Edit Event <em>".$eventName."</em> - Additional Information</h2>";
        require "/var/www/html/Admin/EventSections/AdditionalInformation.php";
        echo "</div><!-- event-form -->";

    }

}
?>

    <div class="single-column-layout" style="width: 73.6px; margin: 0 auto;">
        <button id="formSubmission" class="server-action" type="button" onclick="checkSubmission()">Submit</button>
    </div><!-- single-column-layout -->

</form>

