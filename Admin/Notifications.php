<?php

function action($category){
    if($category == 'jobshop'){
        return "<a href='JobshopConfirm.php' >Confirm Jobshop Card</a>";
    }
    else{
        return "No action required";
    }
}

include "../PageComponents/Head.php";

checkPageIsActive("NotificationsAdmin");

?>

<h2>Notifications</h2>

<?php

$notifications = sqlFetch("SELECT recordID, text, category FROM NotificationsAdmin WHERE audienceAccessLevel <= ".$_SESSION["accessLevel"]." AND (targetIndividual IS NULL OR targetIndividual = '".$_SESSION["userID"]."')", "ASSOC");

echo "
<table>
    <tr>
        <th>Notification</th>
        <th>Action Required</th>
    </tr>";

foreach($notifications as $row){
    echo "
    <tr>
        <td>".$row["text"]."<td>
        <td>".action($row["category"])."</td>
    </tr>";
}

include "/var/www/html/PageComponents/Foot.php";

?>