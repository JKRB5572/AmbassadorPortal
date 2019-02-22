<?php

include "/var/www/html/PageComponents/Head.php";

$verify = null;

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $reportedFrom = validateInput($_GET["page"]);
    $reportedAt = date("Y-m-d H:i:s");
    $reportedBy = $_SESSION["userID"];
    $reportDetails = encrypt(validateInput($_POST["reportDetails"]));
    $verify = sqlInsert("INSERT INTO BugReports(reportedFrom, reportedAt, reportedBy, reportDetails) VALUES ('".$reportedFrom."', '".$reportedAt."', '".$reportedBy."', '".$reportDetails."')", True, True);

}

?>

<h2>Bug Report</h2>

<?php

if($verify === True){
    echo "<p>Report Submitted.</p>";
}
else if($verify === False){
    echo "<p>Report Failed to Submit. Please send an email to comscambassadors@cardiff.ac.uk instead with subject line 'Bug Report'.</p>";
}
else{
    ?>

<p>Please describe the bug you encountered below.</p>

<form method="POST" id="bugReportForm">
    <textarea name="reportDetails" form="bugReportForm" style="width: 300px; height: 120px;"></textarea><br/>
    <button type="submit" class="server-action">Submit</button>
</form>

<?php
    }
?>