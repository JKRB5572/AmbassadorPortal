<?php

require_once "/var/www/html/PageComponents/Requirements.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $table = validateInput($_POST["table"]);
    $id = validateInput($_POST["id"]);

    if($table = "ambassador"){
        if(sqlDelete("DELETE FROM NotificationsAmbassador WHERE recordID = '".$id."'")){
            echo "true";
        }
        else{
            echo "false";
        }
    }
    else if($table = "admin"){
        if(sqlDelete("DELETE FROM NotificationsAdmin WHERE recordID = '".$id."'")){
            echo "true";
        }
        else{
            echo "false";
        }
    }
}