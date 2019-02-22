<?php

require_once "../PHP/Config.php";
require_once "../PHP/CoreFunctions.php";

if(!$isDeveloperPane){
    header("location: index.php");
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(validateInput($_POST["action"]) == "systemAnnouncements"){
        $text = encrypt(validateInput($_POST["messageText"]));
        sqlUpdate("UPDATE SystemAnnouncements SET text = '{$text}' WHERE target = 'pageHeader'");
    }
}

$currentAnnouncements = sqlFetch("SELECT * FROM SystemAnnouncements", "ASSOC");

?>

<h4>System Announcements</h4>

<table>
    <tr>
        <th>Target</th>
        <th>Announcements</th>
    </tr>
    
    <?php
    
    foreach($currentAnnouncements as $announcement){
        echo '
        <tr>
            <td>'.$announcement["target"].'</td>
            <td>'.decrypt($announcement["text"]).'</td>
        </tr>
        ';
    }

    ?>

</table>

<form method="POST">
    <input name="action" value="systemAnnouncements" style="display: none">
    Change Text: <input type="text" name="messageText" style="width: 70%;">
    <button type="submit" class="server-action" style="float: right;">Change</button>
</form>