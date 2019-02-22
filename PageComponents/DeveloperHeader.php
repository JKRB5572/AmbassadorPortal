<?php


if(!isset($_SESSION['userID'])){
    header("location: ../index.php");
}
else if(!isset($_SESSION['accessLevel'])){
    header("location: ../index.php");
}
else if($_SESSION['accessLevel'] != 5){
    header("location: ../Admin/index.php");
}

?>

<nav>
    <ul>
        <div class="left-navigation">
            <li style="border-right: 2px solid white;"><a href="../Admin"><i class="fa fa-home"></i></a></li>
        </div><!-- left-navigation -->

        <div class="right-navigation">
            <li style="float: right"><a href="../PHP/Scripts/Logout.php"><i class="fa fa-sign-out"></i></a></li>
            <li style="float: right; border-left: 2px solid white;"><a class="active"><i class="fa fa-cogs"></i></a></li>
        </div><!-- right-navigation -->
    </ul>
</nav>

<h2>Developer Pane</h2>