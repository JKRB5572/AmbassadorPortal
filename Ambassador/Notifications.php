<?php

function userNotification($text, $id){
    echo "<div id=\"".$id."\" class='ambassador-notification'>".decrypt($text)."<i class='fa fa-remove' onclick='deleteNotificationAmbassador(\"".$id."\")'></i></div>";
}

function systemNotification($text){
    echo "<div class='ambassador-notification'>".decrypt($text)."</div>";
}

include "/var/www/html/PageComponents/Head.php";

checkPageIsActive("NotificationsAmbassador");

?>

<h2>Notifications</h2>

<?php

$notifications = sqlFetch("SELECT recordID, text, category, targetIndividual FROM NotificationsAmbassador WHERE targetIndividual IS NULL OR targetIndividual = '".$_SESSION["userID"]."'", "ASSOC");


foreach($notifications as $notification){
    if(isset($notification["targetIndividual"])){
        userNotification($notification["text"], $notification["recordID"]);
    }
    else{
        systemNotification($notification["text"]);
    }
}

include "/var/www/html/PageComponents/Foot.php";

?>