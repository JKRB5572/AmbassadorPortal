<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";
require_once "/var/www/html/PHP/FormFunctions.php";
require_once "/var/www/html/PHP/SQLFunctions.php";


function testUserAdmin(){
    $details = sqlFetch("SELECT * FROM Admin WHERE adminID = 'TFNJT2dLZWZIQVQ0'", "ASSOC");
    $details = $details[0];

    $_SESSION["testUser"] = true;
    $_SESSION["userID"] = "TFNJT2dLZWZIQVQ0";
    $_SESSION["userType"] = "admin";
    $_SESSION["givenName"] = decrypt($details["givenName"]);
    $_SESSION["forename"] = decrypt($details["forename"]);
    $_SESSION["surname"] = decrypt($details["surname"]);
    $_SESSION["email"] = decrypt($details["email"]);
}

function superAdmin(){
    testUserAdmin();
    $_SESSION["accessLevel"] = 4;
    $_SESSION["leadAmbassador"] = false;
}

function admin(){
    testUserAdmin();
    $_SESSION["accessLevel"] = 3;
    $_SESSION["leadAmbassador"] = false;
}

function officer(){
    testUserAdmin();
    $_SESSION["accessLevel"] = 2;
    $_SESSION["leadAmbassador"] = true;
}

function leadAmbassador(){
    testUserAdmin();
    $_SESSION["accessLevel"] = 1;
    $_SESSION["leadAmbassador"] = true;
}


function ambassador(){
    $details = sqlFetch("SELECT * FROM Ambassadors WHERE universityID = 'TFNJT2dLZVdFd3psN0xMVUxsTT0='", "ASSOC");
    $details = $details[0];

    $_SESSION["testUser"] = true;
    $_SESSION["userID"] = "TFNJT2dLZVdFd3psN0xMVUxsTT0=";
    $_SESSION["accessLevel"] = 0;
    $_SESSION["surname"] = decrypt($details["surname"]);
    $_SESSION["forename"] = decrypt($details["forename"]);
    $_SESSION["givenName"] = decrypt($details["givenName"]);
    $_SESSION["phoneNo"] = decrypt($details["phoneNo"]);
    $_SESSION["programOfStudy"] = $details["programOfStudy"];
    $_SESSION["yearOfStudy"] = $details["yearOfStudy"];
    $_SESSION["driver"] = $details["driver"];
    $_SESSION["tShirtSize"] = $details["tShirtSize"];
    $_SESSION["training"] = json_decode($details["trainingCompleted"]);
    $_SESSION["eventsAttended"] = $details["eventsAttended"];
    $_SESSION["eventsMissed"] = $details["eventsMissed"];
    $_SESSION["timesheets"] = $details["timesheets"];
}


if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(validateInput($_POST["action"]) == "loadTestUser"){
        $userType = validateInput($_POST["userType"]);

        if($userType == "super"){
            superAdmin();
            echo "<script>window.location.replace('/Admin');</script>";
        }
        else if($userType == "admin"){
            admin();
            echo "<script>window.location.replace('/Admin');</script>";
        }
        else if($userType == "officer"){
            officer();
            echo "<script>window.location.replace('/Admin');</script>";
        }
        else if($userType == "lead"){
            leadAmbassador();
            echo "<script>window.location.replace('/Admin');</script>";
        }
        else if($userType == "ambassador"){
            ambassador();
            echo "<script>window.location.replace('/Ambassador');</script>";
        }
    }

}


?>

<h4>Load Test User</h4>

<form method="POST" onkeypress="return event.keycode != 13;">
    <input name="action" value="loadTestUser" style="display: none;">
    <input name="userType" type="radio" value="super"> Super Admin&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="userType" type="radio" value="admin"> Admin&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="userType" type="radio" value="officer"> Officer&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="userType" type="radio" value="lead"> Lead Ambassador&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="userType" type="radio" value="ambassador"> Ambassador&nbsp;&nbsp;&nbsp;&nbsp;
    <div style="width: 73.61px; margin: 0 auto;">
		<button class="server-action">Load</button>
	</div>
</form>