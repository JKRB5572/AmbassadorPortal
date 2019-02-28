<?php

function trainingCompleted($jsonObj, $training){
    if(isset($jsonObj) && in_array($training, $jsonObj)){
        return '<i class="material-icons">check</i>';
    }
    else{
        return '';
    }
}


require_once "../PageComponents/Head.php";

checkPageIsActive("Ambassadors");

?>

<h2>Ambassadors</h2>

<button class="server-action" onclick="location = 'Training.php'"  style="float: right;">Update Training</button>


<?php



$allAmbassadors = sqlFetch("SELECT * FROM Ambassadors", "ASSOC");

foreach($allAmbassadors as $key => $ambassador){
    $allAmbassadors[$key]["email"] = decrypt($ambassador["email"]);
}

$email = array_column($allAmbassadors, "email");
array_multisort($email, SORT_ASC, $allAmbassadors);


?>


<table class='fullwidth'>
    <tr id="tableHeader">
        <th rowspan='2'>Name</th>
        <th rowspan='2'>Email</th>
        <th rowspan='2'>Hours</th>
        <th rowspan='2'>Degree</th>
        <th rowspan='2'>Year of Study</th>
        <th rowspan='2'>Driver</th>
        <th rowspan='2'>T-Shirt Size</th>
        <th colspan='5'>Training</th>
    </tr>
    <tr>
        <th>GrnFt</th>
        <th>LMind</th>
        <th>MiBit</th>
        <th>RasPi</th>
        <th>Srtch</th>
    </tr>


<?php

foreach($allAmbassadors as $ambassador){
    echo "
    <tr ondblclick='window.location=\"ViewAmbassador.php?id=".$ambassador["universityID"]."\";'>
        <td>".returnFullName($ambassador["surname"], $ambassador["forename"], $ambassador["givenName"], true)."</td>
        <td>".$ambassador["email"]."</td>
        <td>".$ambassador["hoursWorked"]."</td>
        <td>".$ambassador["programOfStudy"]."</td>
        <td>".$ambassador["yearOfStudy"]."</td>
        <td>".boolToPolar($ambassador["driver"])."</td>
        <td>".$ambassador["tShirtSize"]."</td>";
    
    $training = json_decode($ambassador["trainingCompleted"]);
    
    echo"
        <td>".trainingCompleted($training, "3")."</td>
        <td>".trainingCompleted($training, "6")."</td>
        <td>".trainingCompleted($training, "4")."</td>
        <td>".trainingCompleted($training, "7")."</td>
        <td>".trainingCompleted($training, "5")."</td>
    </tr>";
}

?>

</table>


<?php

include "../PageComponents/Foot.php";

?>