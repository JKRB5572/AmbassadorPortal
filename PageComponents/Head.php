<?php

require_once "/var/www/html/PageComponents/Requirements.php";

$directory = explode("/", $_SERVER['PHP_SELF']);

//Check that page is not login page
$isLogin = False;

if(count($directory) == 3 && $directory[2] == "Login.php"){
    $isLogin = True;
}

//If not login page and sessions not set logout
if(!isset($_SESSION["accessLevel"]) && $isLogin == False){
    header("location: /PHP/Scripts/Logout.php");
}


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/JavaScript/CoreFunctions.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">    
    <link rel="stylesheet" type="text/css" href="/PageComponents/CSS/Style.css">

    <?php

        if($isLogin == False){

            if($directory[1] == "Developer"){
                echo "<link rel='stylesheet' type='text/css' href='/PageComponents/CSS/StyleDeveloper.css'>";
            }
            else if($_SESSION["accessLevel"] == 0){
                echo "<link rel='stylesheet' type='text/css' href='/PageComponents/CSS/StyleAmbassador.css'>";
            }
            else if($_SESSION["accessLevel"] >= 1){
                echo "<link rel='stylesheet' type='text/css' href='/PageComponents/CSS/StyleAdmin.css'>";
            }

        }

    ?>

    <title><?php pageTitle(); ?></title>
</head>
<body>

<?php

if($isLogin == False){
    if($_SESSION["accessLevel"] == 0){
        include "AmbassadorHeader.php";
    }
    else if($_SESSION["accessLevel"] >= 1){
        include "AdminHeader.php";
    }
}
else{
    include "/var/www/html/PageComponents/PortalHeader.php";
}

?>

<div class="content">