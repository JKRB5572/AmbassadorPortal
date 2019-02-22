<?php

include "/var/www/html/PageComponents/Head.php";

?>

<div class="ambassador-home">

    <h2 style="text-align: left; margin-bottom: 50px;">Hello <?php echo returnName(); ?></h2>

    <p>Events that you can register for are avaliable from the calendar page</p>

    <div class="three-column-layout-row">
        <div class="three-column-layout-left">
            <?php if(checkPageIsActive("MyEventsAmbassador", True) == True){ ?>
                <a href="MyEvents.php">
                <div class="homepage-icon">
                    <i class="fa fa-list-alt"></i>
                    <h2>My Events</h2>
                </div>
                </a>
            <?php } ?>
        </div><!-- three-column-layout-left -->
        <div class="three-column-layout-middle">
            <?php if(checkPageIsActive("CalendarAmbassador", True) == True){ ?>
                <a href="Calendar.php">
                <div class="homepage-icon">
                    <i class="fa fa-calendar"></i>
                    <h2>Calendar</h2>
                </div>
                </a>
            <?php } ?>
        </div><!-- three-column-layout-middle-->
        <div class="three-column-layout-right">
            <?php if(checkPageIsActive("ResourcesAmbassador", True) == True){ ?>
                <a href="Resources.php">
                <div class="homepage-icon">
                    <i class="fa fa-folder-open"></i>
                    <h2>Resources</h2>
                </div>
                </a>
            <?php } ?>
        </div><!-- three-column-layout-right -->
   </div><!-- three-column-layout-row-->

<div><!-- ambassador-home -->

<?php

include "../PageComponents/Foot.php";

?>