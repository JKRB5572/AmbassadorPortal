<?php

require_once "../PageComponents/Head.php";

checkPageIsActive("JobshopConfirm");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST["confirm"]) && isset($_POST["universityID"])){
        sqlUpdate("UPDATE Ambassadors SET jobshopConfirmed = 1 WHERE universityID = '".$_POST["universityID"]."'");
        sqlUpdate("UPDATE Jobshop SET approvedBy = '".$_SESSION["userID"]."' WHERE universityID = '".$_POST["universityID"]."'");
    }
    else if(isset($_POST["reject"]) && isset($_POST["universityID"])){
        sqlUpdate("UPDATE Ambassadors SET jobshopConfirmed = -1 WHERE universityID = '".$_POST["universityID"]."'");
        sqlUpdate("UPDATE Jobshop SET approvedBy = '".$_SESSION["userID"]."' WHERE universityID = '".$_POST["universityID"]."'");
    }

    $url = "http://".$_SERVER["HTTP_HOST"]."/PHP/AutomaticScripts/updateJobshop.php";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_exec($ch);
    curl_close($ch);

}

$ambassador = sqlFetch("SELECT cardImage, Ambassadors.universityID as universityID, surname, forename, givenName FROM Jobshop, Ambassadors WHERE Jobshop.universityID = Ambassadors.universityID AND jobshopConfirmed = 0 LIMIT 1", "ASSOC");


?>

<div class="jobshopConfirm">

<h2>Jobshop Card Confirmation</h2>

<?php

if(count($ambassador) == 0){
    echo "<p>There are no ambassadors pending Jobshop Card approval.</p>";
}
else{

?>

<div class="two-column-layout-left">
    <h4>Ambassador Details</h4>
    <table>
        <tr>
            <th>Surname</th>
            <td><?php echo decrypt($ambassador[0]["surname"]); ?></td>
            
        </tr>
        <tr>
            <th>Forename</th>
            <td><?php echo decrypt($ambassador[0]["forename"]); ?></td>
            
        </tr>
        <tr>
            <th>Given Name</th>
            <td><?php echo decrypt($ambassador[0]["givenName"]); ?></td>
        </tr>
        <tr>
            <th>Expected Finish</th>
            <td>
                <?php

                if( (int)date("m") >= 9 ){
                    $year = (int)date("y") + 1;
                }
                else{
                    $year = (int)date("y");
                }

                echo "August ".$year;

                ?>
            </td>
        </tr>
    </table>
</div><!-- two-clomun-layout-left -->

<div class='two-column-layout-right'>
    <h4>Jobshop Card</h4>
    <img src='data:image/jpg;base64,<?php echo base64_encode($ambassador[0]['cardImage']); ?>' />
</div><!-- two-column-layout-right -->

<div class='single-column-layout'>

    <form method="POST" action="JobshopConfirm.php">
        <input type="hidden" value="<?php echo $ambassador[0]["universityID"]; ?>" name="universityID">
        <div style='text-align: center;'>
            <input type="submit" value="Confirm Jobshop Card" name="confirm">
            <input type="submit" value="Reject Jobshop Card" name="reject" style='background-color: rgb(211, 55, 74);'>
        </div>
    </form>

</div><!-- single-column-layout -->

</div><!-- jobshopConfirm -->

<?php

}

require_once "../PageComponents/Foot.php";

?>