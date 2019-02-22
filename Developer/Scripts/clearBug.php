<?php

require_once "/var/www/html/PageComponents/Requirements.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $id = validateInput($_POST["id"]);
    sqlDelete("DELETE FROM BugReports WHERE reportID = '".$id."'");
    echo "True";
}

?>