<?php

include "../PageComponents/Head.php";

?>

<h2>Developer Pane</h2>

<?php

$isDeveloperPane = True;

echo "<p>Time at page load: ".date('Y-m-d H:i:s');

?>

<div class="developer">

    <div class="pane">
        <?php include "bugReports.php"; ?>
    </div>

    <div class="two-column-layout-left">
        <div class="pane">
            <?php include "systemAnnouncements.php"; ?>
        </div>
        <div class="pane">
            <?php include "pageStatus.php"; ?>
        </div>
    </div><!-- two-column-layout-left -->

    <div class="two-column-layout-right">
        <div class="pane">
            <?php include "cronJobStatus.php"; ?>
        </div>
        <div class="pane">
            <?php include "sessions.php"; ?>
        </div>
        <div class="pane">
            <?php include "encryption.php"; ?>
        </div>
        <div class="pane">
            <?php include "testUser.php"; ?>
        </div>
    </div><!-- two-column-layout-right -->

</developer>