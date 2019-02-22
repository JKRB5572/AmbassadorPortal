<?php 

require_once "/var/www/html/PHP/Config.php";

if(isset($_SESSION['userType'])){

    if($_SESSION['userType'] == "ambassador"){
        header("location: Ambassador/index.php");
    }
    else if(isset($_SESSION['userType']) && $_SESSION['userType'] == "admin"){
        header("location: Admin/index.php");
    }
    else{
        header("location: PHP/Scripts/Logout.php");
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="JavaScript/CoreFunctions.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">    
    <link rel="stylesheet" type="text/css" href="/PageComponents/CSS/Style.css">
    <title>STEM Ambassador Portal</title>
</head>
<body>

    <?php

    require_once "/var/www/html/PageComponents/PortalHeader.php";

    ?>

    <div class="content">

        <div class="two-column-layout-left">
            <div class="portal-home-ambassador">
                <a href="Ambassador/Login.php">
                    <h4>Ambassador<br/>Portal</h4>
                </a>
            </div>
        </div><!-- two-column-layout-left -->

        <div class="two-column-layout-right">
            <div class="portal-home-admin">
                <a href="Admin/Login.php">
                    <h4>Admin<br/>Portal</h4>
                </a>
            </div>
        </div><!-- two-column-layout-right -->
    
    </div>
</body>