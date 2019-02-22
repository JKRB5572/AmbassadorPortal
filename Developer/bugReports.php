<?php

require_once "../PHP/Config.php";
require_once "../PHP/CoreFunctions.php";

if(!$isDeveloperPane){
    header("location: index.php");
}

$bugReports = sqlFetch("SELECT * FROM BugReports", "ASSOC");

?>

<script>

function clearBug(id, text){
    var confirmation = confirm("Are you sure you wish to clear bug report " + id + ": " + text + "?");
    if(confirmation == true){ 
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                if(this.responseText == "True"){
                    $("#bugRow"+id).hide();
                }
                else{
                    console.log(this.responseText);
                }
            }
        };
        xmlhttp.open("POST", "/Developer/Scripts/clearBug.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id);
    }
}

</script>

<h4>Bug Reports</h4>

<?php

if(sizeof($bugReports) > 0){

    ?>

    <table class="fullwidth">
        <tr>
            <th>Page</th>
            <th>Time</th>
            <th>Details</th>
            <th>Reported By</th>
            <th>Actions</th>
        </tr>

        <?php

        foreach($bugReports as $bug){
            $reportDetails = decrypt($bug["reportDetails"]);
            $reportDetails = str_replace("'", "`", $reportDetails);

            if($bug["reportedBy"] == "SYSTEM"){
                $name = "<strong><span style='color: red;'>SYSTEM</span></strong>";
            }
            else{
                $name = sqlFetch("SELECT surname, forename FROM Admin WHERE adminID = '".$bug["reportedBy"]."'", "ASSOC");
                if(sizeof($name) > 0){
                    $name = decrypt($name[0]["forename"])." ".decrypt($name[0]["surname"]);
                }
                else{
                    $name = "";
                }
            }

            echo '
            <tr id="bugRow'.$bug["reportID"].'">
                <td>'.$bug["reportedFrom"].'</td>
                <td>'.$bug["reportedAt"].'</td>
                <td>'.$reportDetails.'</td>
                <td>'.$name.'</td>
                <td class="action" onclick="clearBug(\''.$bug["reportID"].'\', \''.$reportDetails.'\')">Clear</td>
            </tr>
            ';
        }

        ?>

    </table>

    <?php

}
else{
    echo "<p style='color: green;'>No bugs have been reported</p>";
}