<?php

function role($accessLevel){
    if($accessLevel == 1){
        return "Lead Ambassador";
    }
    else if($accessLevel == 2){
        return "Delivery Officer";
    }
    else if($accessLevel == 3){
        return "Admin";
    }
    else if($accessLevel == 4){
        return "Super Admin";
    }
    else if($accessLevel == 5){
        return "Developer";
    }

    else if($accessLevel == 0 && isset($_SESSION["accessLevel"])){
        if($_SESSION["accessLevel"] == 1){
            return "Lead Ambassador (Test User)";
        }
        else if($_SESSION["accessLevel"] == 2){
            return "Delivery Officer (Test User)";
        }
        else if($_SESSION["accessLevel"] == 3){
            return "Admin (Test User)";
        }
        else if($_SESSION["accessLevel"] == 4){
            return "Super Admin (Test User)";
        }
    }
    else{
        return "ERROR";
    }
}

include "../PageComponents/Head.php";

$userDetails = sqlFetch("SELECT * FROM Admin WHERE adminID = '".$_SESSION["userID"]."'", "ASSOC");
$userDetails = $userDetails[0];

?>

<h2>My Account</h2>

<table>
    <tr>
        <th>Surname</th>
        <td><?php echo decrypt($userDetails["surname"]); ?></td>
    </tr>
    <tr>
        <th>Forename(s)</th>
        <td><?php echo decrypt($userDetails["forename"]); ?></td>
    </tr>
    <tr>
        <th>Given Name</th>
        <td><?php echo decrypt($userDetails["givenName"]); ?></td>
    </tr>
    <tr>
        <th>Email Address</th>
        <td><?php echo decrypt($userDetails["email"]); ?></td>
    </tr>
    <tr>
        <th>Access Level</th>
        <td><?php echo role($userDetails["accessLevel"]); ?></td>
    </tr>
</table>

<?php

include "../PageComponents/Foot.php";

?>