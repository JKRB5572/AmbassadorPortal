<?php

include "../PageComponents/Head.php";

?>

<div class="admin-home">

    <h2 style="text-align: left; margin-bottom: 50px;">Hello <?php echo returnName(); ?></h2>

    <div class="three-column-layout-row">
        <div class="three-column-layout-left">
            <?php if(checkPageIsActive("MyEventsAdmin", True) == True){ ?>
                <a href="MyEvents.php">
                <div class="homepage-icon">
                    <i class="fa fa-list-alt"></i>
                    <h2>My Events</h2>
                </div>
                </a>
            <?php } ?>
        </div><!-- three-column-layout-left -->
        <div class="three-column-layout-middle">
            <?php if(checkPageIsActive("CalendarAdmin", True) == True){ ?>
                <a href="Calendar.php">
                <div class="homepage-icon">
                    <i class="fa fa-calendar"></i>
                    <h2>Calendar</h2>
                </div>
                </a>
            <?php } ?>
        </div><!-- three-column-layout-middle-->
        <div class="three-column-layout-right">
            <?php if(checkPageIsActive("AddEvent", True) == True){ ?>
                <a href="AddEvent.php">
                <div class="homepage-icon">
                    <i class="fa fa-calendar-plus-o"></i>
                    <h2>Add Event</h2>
                </div>
                </a>
            <?php } ?>
        </div><!-- three-column-layout-right -->
    </div><!-- three-column-layout-row-->
    <div class="three-column-layout-row">
        <div class="three-column-layout-left">
            <?php if(checkPageIsActive("Ambassadors", True) == True){ ?>
                <a href="Ambassadors.php">
                <div class="homepage-icon">
                    <i class="fa fa-group"></i>
                    <h2>Ambassadors</h2>
                </div>
                </a>
            <?php } ?>
        </div><!-- three-column-layout-left -->
        <div class="three-column-layout-middle">
            <?php if(checkPageIsActive("ResourcesAdmin", True) == True){ ?>
                <a href="Resources.php">
                <div class="homepage-icon">
                    <i class=" fa fa-folder-open"></i>
                    <h2>Resources</h2>
                </div>
                </a>
            <?php } ?>
        </div>
        <div class="three-column-layout-right">
        </div>
    </div><!-- three-column-layout-row -->

</div><!-- admin-home -->

<?php

include "../PageComponents/Foot.php";

?>