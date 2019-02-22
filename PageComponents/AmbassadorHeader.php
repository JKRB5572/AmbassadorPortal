<?php

if(!isset($_SESSION['userID'])){
    header("location: ../index.php");
}
else if($_SESSION['accessLevel'] > 0){
    header("location: ../Admin/index.php");
}
else if(isset($_SESSION['jobshopPending']) && substr($_SERVER["PHP_SELF"], -11) != "Jobshop.php"){
    header("location: ../Ambassador/Jobshop.php");
}


$notifications = count(sqlFetch("SELECT recordID FROM NotificationsAmbassador WHERE targetIndividual IS NULL OR targetIndividual = '".$_SESSION["userID"]."'", "NUM"));

?>

<nav>
    <div class="navigation-left">
        <ul>
        <?php

        if(!isset($_SESSION['jobshopPending'])){

        ?>
            <!-- Left Navigation -->
            <li style="border-right: 2px solid white;"><?php returnAnchor("index.php", "Ambassador"); ?><i class="fa fa-home"></i></a></li>

            <?php
            
            if(checkPageIsActive("MyEventsAmbassador", True) == True){
                echo "<li>".returnAnchor("MyEvents.php", "Ambassador")."My Events</a></li>";
            }

            if(checkPageIsActive("CalendarAmbassador", True) == True){
                echo "<li>".returnAnchor("Calendar.php", "Ambassador")."Calendar</a></li>";
            }

            if(checkPageIsActive("ResourcesAmbassador", True) == True){
                echo "<li>".returnAnchor("Resources.php", "Ambassador")."Resources</a></li>";
            }

        }

        ?>

            <!-- Right Navigation -->

            <li style="float: right"><a href="../PHP/Scripts/Logout.php"><i class="fa fa-sign-out"></i></a></li>

        <?php 

        if(!isset($_SESSION['jobshopPending'])){

            if(checkPageIsActive("NotificationsAmbassador", True) == True){
                echo '<li style="float: right">'.returnAnchor("Notifications.php", "Ambassador").'<i class="fa fa-bell" data-notifications="'.$notifications.'"></i></a></li>';
            }

            if(checkPageIsActive("MyAccountAmbassador", True) == True){
                echo '<li style="float: right">'.returnAnchor("MyAccount.php", "Ambassador").'<i class="fa fa-user-circle-o"></i></a></li>';
            }

        }

        ?>
        </ul>
    </div>
    <?php deploymentVersion(); ?>
</nav>
