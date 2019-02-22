<?php

function deploymentVersion(){
    $version = "0.1.1";
    echo "<p style='color: #555; background-color: white; font-size: 12pt; padding-top: 5px; padding-left: 5px; margin-bottom: 0px'><strong>Beta Test (Version 1.0.1)</strong>&nbsp;&nbsp;&nbsp;&nbsp<em>Please report any bugs you encounter with the button below.</a></em>";

    $announcement = sqlFetch("SELECT text FROM SystemAnnouncements WHERE target = 'pageHeader'", "NUM");
    if(count($announcement[0]) > 0){
        echo "<br/><strong>".decrypt($announcement[0][0])."</strong>";
    }

    echo "</p>";

    $page = substr($_SERVER['PHP_SELF'], 0, -4);
    if($page != "/BugReport"){
        echo "<div style='background-color: white; padding-left: 5px;'><a href='/BugReport.php?page=".$page."'><button class='client-action'>Report Bug</button></a></div>";
    }
}


//Set time zone
date_default_timezone_set("UTC");

//Session settings
ini_set('session.cookie_httponly', 1);
//ini_set('session.cookie_secure', 1); !!! NEEDS TO BE RE-ENABLED FOR RELEASE !!!
ini_set('session.cookie_lifetime', 0);
session_start();


//Define today's date
$date = date("Y-m-d");
define('todayDate', $date);


$link = mysqli_connect($_ENV["DATABASE_SERVER"], $_ENV["DATABASE_USERNAME"], $_ENV["DATABASE_PASSWORD"], $_ENV["DATABASE_NAME"]);
if($link === false){
    die("ERROR<br/>Could not connect to SQL database<br/><br/>Additional Details: " + mysqli_connect_error());
}



//Function to login using ldap
function ldapAuthentication($userID, $userPassword){

    $ldapconn = ldap_connect("ldap://ldap-jccs.cardiff.ac.uk") or die("Could not connect to LDAP server.");

    if($ldapconn){

        $result = ldap_search($ldapconn, "o=CF", "uid=".$userID, array("dn"));
        $info = ldap_get_entries($ldapconn, $result);
        $userdn = $info[0]["dn"];

        if(ldap_start_tls($ldapconn)){
            $ldapbind = ldap_bind($ldapconn, $userdn, $userPassword);
            if($ldapbind){
                return True;
            }
            else{
                return False;
            }
        }
    }

}

?>