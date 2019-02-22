<?php

function trainingCompleted($jsonObj, $training){
    if(in_array($training, $jsonObj)){
        return '<i class="material-icons">check</i>';
    }
    else{
        return '';
    }
}

require_once "../PageComponents/Head.php";

checkPageIsActive("Ambassadors");

if(isset($_GET["id"])){
    $id = validateInput($_GET["id"]);
    $ambassadorDetails = sqlFetch("SELECT * FROM Ambassadors WHERE universityID = '".$id."'", "ASSOC");

    if(count($ambassadorDetails) <= 0){
        header("location: /Admin/Ambassadors.php");
    }
    else{
        $ambassadorDetails = $ambassadorDetails[0];
    }
}
else{
    header("location: /Admin/Ambassadors.php");
}

?>

<h2>Ambassador Details</h2>

<table>

<?php

    echoTableRow($ambassadorDetails["universityID"], "University ID", true);
    echoTableRow($ambassadorDetails["email"], "Email Address", true);
    echoTableRow($ambassadorDetails["surname"], "Surname", true);
    echoTableRow($ambassadorDetails["forename"], "Forename(s)", true);
    echoTableRow($ambassadorDetails["givenName"], "Given Name", true);
    echoTableRow($ambassadorDetails["phoneNo"], "Phone Number", true);
    echoTableRow($ambassadorDetails["programOfStudy"], "Program of Study");
    echoTableRow($ambassadorDetails["yearOfStudy"], "Year of Study");
    echoTableRow($ambassadorDetails["tShirtSize"], "T-Shirt Size");
    echoTableRow(boolToPolar($ambassadorDetails["driver"]), "Driver");

?>


</table>