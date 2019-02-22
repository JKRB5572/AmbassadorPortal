<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";
require_once "/var/www/html/PHP/FormFunctions.php";
require_once "/var/www/html/PHP/SQLFunctions.php";

require_once "/var/www/html/PHP/EditEventFunctions.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $id = validateInput($_POST["id"]);
    $value = validateInput($_POST["value"]);

    $requestIsValid = checkEventCompletenessIDOnly($id);

    if($requestIsValid == true){
        $requestComplete = sqlUpdate("UPDATE EventPrimary SET visibility = '".$value."' WHERE eventID = '".$id."'", true, true);
        if($requestComplete == true){
            echo $value;
        }
        else{
            echo -1;
        }
    }
    else{
        echo -2;
    }
}
else{
    echo -3;
}

?>