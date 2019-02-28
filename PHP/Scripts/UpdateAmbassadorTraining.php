<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";
require_once "/var/www/html/PHP/FormFunctions.php";
require_once "/var/www/html/PHP/SQLFunctions.php";

if(isset($_POST["data"])){
    $data = $_POST["data"];
    $data = json_decode($data);

    foreach($data as $ID => $training){

        if($training[0] == "undefined"){
            sqlUpdate("UPDATE Ambassadors SET trainingCompleted = NULL WHERE universityID = '".$ID."'", True);
        }
        else{
            //Check if LEGO training completed and add LEGO WeDo training if true
            if(in_array("6", $training)){
                array_push($training, "10");
            }
            $training = json_encode($training);
            sqlUpdate("UPDATE Ambassadors SET trainingCompleted = '".$training."' WHERE universityID = '".$ID."'", True);
        }
    }

}

?>