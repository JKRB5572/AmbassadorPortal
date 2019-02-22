<?php


if(!isset($_SESSION['userID'])){
    header("location: ../index.php");
}
else if(isset($_SESSION['access']) && $_SESSION['access'] === "ambassador"){
    header("location: ../Ambassador/index.php");
}


$notifications = count(sqlFetch("SELECT recordID FROM NotificationsAdmin WHERE audienceAccessLevel <= ".$_SESSION["accessLevel"]." AND (targetIndividual IS NULL OR targetIndividual = '".$_SESSION["userID"]."')", "NUM"));

?>

<nav>
    <ul>
        <div class="left-navigation">
            <li style="border-right: 2px solid white;"><?php returnAnchor("index.php", "Admin"); ?><i class="fa fa-home"></i></a></li>

            <?php
            
            if($_SESSION["leadAmbassador"] == True && checkPageIsActive("MyEventsAdmin", True) == True){
                echo "<li>".returnAnchor("MyEvents.php", "Admin")."My Events</a></li>";
            }
            
            if(checkPageIsActive("CalendarAdmin", True) == True){
                echo '<li>'.returnAnchor("Calendar.php", "Admin").'Calendar</a></li>';
            }

            if(checkPageIsActive("Ambassadors", True) == True){
                echo '<li>'.returnAnchor("Ambassadors.php", "Admin").'Ambassadors</a></li>';
            }

            if(checkPageIsActive("ResourcesAdmin", True) == True){
                echo '<li>'.returnAnchor("Resources.php", "Admin").'Resources</a></li>';
            }

            ?>
        </div><!-- left-navigation -->

        <div class="right-navigation">
            <li style="float: right"><a href="/PHP/Scripts/Logout.php"><i class="fa fa-sign-out"></i></a></li>

            <?php

            if($_SESSION["accessLevel"] == 5){
                echo '<li style="float: right;">'.returnAnchor("index.php", "Developer").'<i class="fa fa-cogs"></i></a></li>';
                echo '<li style="float: right;">'.returnAnchor("Metrics.php", "Developer").'<i class="fa fa-bar-chart"></i></a></li>';
            }
            
            if(checkPageIsActive("NotificationsAdmin", True) == True){
                echo '<li style="float: right">'.returnAnchor("Notifications.php", "Admin").'<i class="fa fa-bell" data-notifications="'.$notifications.'"></i></a></li>';
            }

            if(checkPageIsActive("MyAccountAdmin", True) == True){
                echo '<li style="float: right; border-left: 2px solid white;">'.returnAnchor("MyAccount.php", "Admin").'<i class="fa fa-user-circle-o"></i></a></li>';
            }
                
            if(checkPageIsActive("AddAmbassador", True) == True){
                echo '<li style="float: right">'.returnAnchor("AddAmbassador.php", "Admin").'<i class="fa fa-user-plus"></i></a></li>';
            }

            if(checkPageIsActive("AddEvent", True) == True){
                echo '<li style="float: right">'.returnAnchor("AddEvent.php", "Admin").'<i class="fa fa-calendar-plus-o"></a></i></li>';
            }

            ?>
        </div><!-- right-navigation -->
    </ul>
    <?php deploymentVersion(); ?>
</nav>