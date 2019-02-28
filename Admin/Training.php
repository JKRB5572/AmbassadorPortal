<?php

function trainingCompleted($jsonObj, $training){
    if(isset($jsonObj)){
        if(in_array($training, $jsonObj)){
            return "checked";
        }
        else{
            return "";
        }
    }
}

include "../PageComponents/Head.php";

checkPageIsActive("Training");

?>

<script>

var header = document.getElementById("tableHeader");
var sticky = header.offsetTop;

window.onscroll = function () {
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }
};

</script>


<h2>Update Ambassador Training</h2>

<button class="server-action" onclick="updateAmbassadorTraining()" style="float: right;">Confirm Changes</button>

<?php

$allAmbassadors = sqlFetch("SELECT universityID, email, surname, forename, givenName, trainingCompleted FROM Ambassadors", "ASSOC");

foreach($allAmbassadors as $key => $ambassador){
    $allAmbassadors[$key]["email"] = decrypt($ambassador["email"]);
}

$email = array_column($allAmbassadors, "email");
array_multisort($email, SORT_ASC, $allAmbassadors);

$training = sqlFetch("SELECT topicID, topicName FROM Topics WHERE trainingRequired = 1", "ASSOC");

?>

<script>var ambassadors = [<?php

$jsArray = "";
foreach($allAmbassadors as $ambassador){
    $jsArray .= "'".$ambassador['universityID']."', ";
}

echo substr($jsArray, 0, -2);

?>];</script>

<?php

echo "
<table class='fullwidth'>
    <tr>
        <th rowspan='2'>Name</th>
        <th colspan='5'>Training Status</th>
    </tr>
    <tr>
        <th>Greenfoot</th>
        <th>LEGO Mindstorms</th>
        <th>Micro:bits</th>
        <th>Raspberry Pi's</th>
        <th>Scratch</th>
    </tr>";


foreach($allAmbassadors as $ambassador){
    echo "
    <tr>
        <td>".returnFullName($ambassador["surname"], $ambassador["forename"], $ambassador["givenName"], true)."</td>
        <td><input type='checkbox' id='".$ambassador["universityID"]."Greenfoot' ".trainingCompleted(json_decode($ambassador["trainingCompleted"]), 3)." name=''></td>
        <td><input type='checkbox' id='".$ambassador["universityID"]."LegoMindstorms' ".trainingCompleted(json_decode($ambassador["trainingCompleted"]), 6)." name=''></td>
        <td><input type='checkbox' id='".$ambassador["universityID"]."MicroBits' ".trainingCompleted(json_decode($ambassador["trainingCompleted"]), 4)." name=''></td>
        <td><input type='checkbox' id='".$ambassador["universityID"]."RaspberryPis' ".trainingCompleted(json_decode($ambassador["trainingCompleted"]), 7)." name=''></td>
        <td><input type='checkbox' id='".$ambassador["universityID"]."Scratch' ".trainingCompleted(json_decode($ambassador["trainingCompleted"]), 5)." name=''></td>        
    </tr>";
    
}

echo "</table>";

?>


<script>

function updateAmbassadorTraining(){
    var updatedTraining = "{"; 
    for(var i = 0; i < ambassadors.length; i++){
        var training = [];
        if(document.getElementById(ambassadors[i]+"Greenfoot").checked == true){
            training.push(3);
        }
        if(document.getElementById(ambassadors[i]+"LegoMindstorms").checked == true){
            training.push(6);
        }
        if(document.getElementById(ambassadors[i]+"MicroBits").checked == true){
            training.push(4);
        }
        if(document.getElementById(ambassadors[i]+"RaspberryPis").checked == true){
            training.push(7);
        }
        if(document.getElementById(ambassadors[i]+"Scratch").checked == true){
            training.push(5);
        }
        updatedTraining += '"'+ambassadors[i]+'":[' + trainingAsString(training) + '], ';
    }
    updatedTraining = updatedTraining.substring(0, updatedTraining.length-2) + "}";


    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            window.location.assign("/Admin/Ambassadors.php");
        }
    }
    xmlhttp.open("POST", "../PHP/Scripts/UpdateAmbassadorTraining.php");
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("data="+updatedTraining);
}

function trainingAsString(training){
    returnString = "";
    for(i = 0; i < training.length - 1; i++){
        returnString += '"' + training[i] + '", ';
    }
    returnString += '"' + training[i] + '"';
    return returnString; 
}

</script>

<?php

include "../PageComponents/Foot.php";

?>
