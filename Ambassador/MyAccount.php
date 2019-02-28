<?php

require_once "/var/www/html/PageComponents/Head.php";

function trainingCompleted($training){
    if(in_array($training, $_SESSION["training"])){
        return '<i class="material-icons">check</i>';
    }
    else{
        return '';
    }
}

$hoursWorked = sqlFetch("SELECT hoursWorked FROM Ambassadors WHERE UniversityID = '".$_SESSION["userID"]."'", "NUM");
$allTopics = sqlFetch("SELECT topicID, topicName FROM Topics WHERE trainingRequired = 1 ORDER BY topicName", "ASSOC");


if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(isset($_POST["surname"])){ $surname = encrypt(validateInput($_POST["surname"])); } else { $surname = null; }
    if(isset($_POST["forename"])){ $forename = encrypt(validateInput($_POST["forename"])); } else { $forename = null; }
    if(isset($_POST["givenName"])){ $givenName = encrypt(validateInput($_POST["givenName"])); } else { $givenName = null; }
    if(isset($_POST["tShirtSize"])){ $tShirtSize = validateInput($_POST["tShirtSize"]); } else { $tShirtSize = null; }
    if(isset($_POST["driver"])){ $driver = validateInput($_POST["driver"]); } else { $driver = null; }

    $queryValues = array(); 

    if($surname){
        $queryValues[] = "surname = '{$surname}'";
    }
    if($forename){
        $queryValues[] = "forename = '{$forename}'";
    }

    if($givenName){
        $queryValues[] = "givenName = '{$givenName}'";
    }
    else{
        $queryValues[] = "givenName = NULL";
    }

    if($tShirtSize){
        $queryValues[] = "tShirtSize = '{$tShirtSize}'";
    }
    if($driver){
        $queryValues[] = "driver = '{$driver}'";
    }

    
    if(count($queryValues) > 0){
        $query = "";

        for($i = 0; $i < count($queryValues) - 1; $i++){
            $query .= $queryValues[$i].", ";
        }
        $query .= $queryValues[$i];

        $query = "UPDATE Ambassadors SET {$query} WHERE universityID = '".$_SESSION["userID"]."'";

        if(sqlUpdate($query, True, True) == False){
            logSystemError("/Ambassador/MyAccount", "Error executing sql update with query: {$query}");
            echo "<p style='color: red;'><strong>A system error has occured whilst writing to the database. Please seek help from the system adminstrator and quote the following error code: 1-P-MA-0.</strong></p>";
        }
        else{
            $updatedValues = sqlFetch("SELECT surname, forename, givenName, tShirtSize, driver FROM Ambassadors WHERE universityID = '".$_SESSION["userID"]."'", "ASSOC");
            
            $_SESSION["surname"] = decrypt($updatedValues[0]["surname"]);
            $_SESSION["forename"] = decrypt($updatedValues[0]["forename"]);
            $_SESSION["givenName"] = decrypt($updatedValues[0]["givenName"]);
            $_SESSION["tShirtSize"] = $updatedValues[0]["tShirtSize"];
            $_SESSION["driver"] = $updatedValues[0]["driver"];
        }
    }
}

?>

<script>

var surname = "<?php echo $_SESSION["surname"]; ?>";
var forename = "<?php echo $_SESSION["forename"]; ?>";
var givenName = "<?php echo $_SESSION["givenName"]; ?>";
var tShirtSize = "<?php echo $_SESSION["tShirtSize"]; ?>";
var driver = "<?php echo boolToPolar($_SESSION["driver"]); ?>";


function isSelected(optionValue, suppliedValue){
    if(optionValue == suppliedValue){
        return "selected";
    }
}


var tShirtSelection = "<select id='editTShirtSize' name='tShirtSize'>";
tShirtSelection += "<option value='XS' " + isSelected("XS", tShirtSize) + ">XS</option>";
tShirtSelection += "<option value='S' " + isSelected("S", tShirtSize) + ">S</option>";
tShirtSelection += "<option value='M' " + isSelected("M", tShirtSize) + ">M</option>";
tShirtSelection += "<option value='L' " + isSelected("L", tShirtSize) + ">L</option>";
tShirtSelection += "<option value='XL' " + isSelected("XL", tShirtSize) + ">XL</option>";
tShirtSelection += "</select>";


var driverSelection = "<select id='editDriver' name='driver'>";
driverSelection += "<option value='0' " + isSelected("", driver) + ">No</option>";
driverSelection += "<option value='1' " + isSelected("", driver) + ">Yes, with access to a car and buisness insurance</option>";
driverSelection += "</selection>";


function editDetails(){
    document.getElementById("surname").innerHTML = "<input id='editSurname' type='text' name='surname' value='" + surname + "'>";
    document.getElementById("forename").innerHTML = "<input id='editForename' type='text' name='forename' value='" + forename + "'>";
    document.getElementById("givenName").innerHTML = "<input id='editGivenName' type='text' name='givenName' value='" + givenName + "'>";
    document.getElementById("tShirtSize").innerHTML = tShirtSelection;
    document.getElementById("driver").innerHTML = driverSelection;

    document.getElementById("editButton").style.display = "none";
    document.getElementById("cancelButton").style.display = "block";
    document.getElementById("editText").style.display = "block";
    document.getElementById("errorText").style.display = "block";
    document.getElementById("confirmEdit").style.display = "block";
}

function cancelEdit(){
    document.getElementById("surname").innerHTML = surname;
    document.getElementById("forename").innerHTML = forename;
    document.getElementById("givenName").innerHTML = givenName;
    document.getElementById("tShirtSize").innerHTML = tShirtSize;
    document.getElementById("driver").innerHTML = driver;

    document.getElementById("editButton").style.display = "block";
    document.getElementById("cancelButton").style.display = "none";
    document.getElementById("editText").style.display = "none";
    document.getElementById("errorText").style.display = "none";
    document.getElementById("confirmEdit").style.display = "none";
}

function confirmEdit(){
    var surname = document.getElementById("editSurname").value;
    var forename = document.getElementById("editForename").value;
    var givenName = document.getElementById("editGivenName").value;

    var errorText = "";

    if(!surname.match(/^[A-Z][a-z]+(|\-[A-Z][a-z]+)$/)){
        errorText += "Please enter a valid surname<br/>";
    }

    if(!forename.match(/^[A-Z][a-z]+(|(\ [A-Z][a-z]+)+)$/)){
        errorText += "Please enter a valid forename<br/>";
    }

    if(givenName != ""){
        if(!givenName.match(/^[A-Z][a-z]+$/)){
            errorText += "Please enter a valid given name<br/>";
        }
    }

    document.getElementById("errorText").innerHTML = errorText;

    if(errorText == ""){
        submitForm();
    }
}

function submitForm(){
    var form = document.getElementById("editDetails");
    form.submit();
}

</script>

<h2>My Account</h2>

<br/>

<button id="editButton" class="client-action" onclick="editDetails()" style="float: right;">Edit</button>
<button id="cancelButton" class="client-action" onclick="cancelEdit()" style="display: none; float: right;">Cancel</button>

<br/>

<p id="errorText" style="display: none; color: red;"></p>

<form id="editDetails" method="POST">

    <table>
        <tr>
            <th>Surname</th>
            <td id="surname"><?php echo $_SESSION["surname"]; ?></td>
        </tr>
        <tr>
            <th>Forename(s)</th>
            <td id="forename"><?php echo $_SESSION["forename"]; ?></td>
        </tr>
        <tr>
            <th>Given Name</th>
            <td id="givenName"><?php echo $_SESSION["givenName"]; ?></td>
        </tr>
        <tr>
            <th>Email Address</th>
            <td><?php echo $_SESSION["email"]; ?></td>
        <tr>
            <th>T-Shirt Size</th>
            <td id="tShirtSize"><?php echo $_SESSION["tShirtSize"]; ?></td>
        </tr>
        <tr>
            <th>Program of Study</th>
            <td><?php echo $_SESSION["programOfStudy"]; ?></td>
        </tr>
        <tr>
            <th>Year of Study</th>
            <td><?php echo $_SESSION["yearOfStudy"]; ?></td>
        </tr>
        <tr>
            <th>Driver</th>
            <td id="driver"><?php echo boolToPolar($_SESSION["driver"]); ?></td>
        </tr>
        <tr>
            <th>Hours Worked</th>
            <td><?php echo $hoursWorked[0][0]; ?></td>
        </tr>
    </table>

</form>

<br/><p id="editText" style="display: none;"><em>To change your email address, program of study or year of study please send an email to comscambassadors@cardiff.ac.uk</em></p>

<button id="confirmEdit" class="server-action" onclick="confirmEdit()" style="display: none; float: right;">Confirm</button>

<br/><br/><h4>Training Completed</h4><br/>

<table>
    <tr>
        <?php

        foreach($allTopics as $topic){
            echo "<th>".$topic["topicName"]."</th>";
        }

        ?>
    </tr>
    <tr>
        <?php

        foreach($allTopics as $topic){
            echo "<td>".trainingCompleted($topic["topicID"]);
        }

        ?>
    </tr>
</table>
<br/>
<p><em>If you believe the above information about your completed training is incorrect please send an email to comscambasadors@cardiff.ac.uk</em></p>