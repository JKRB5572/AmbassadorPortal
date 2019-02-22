<?php

include "../PageComponents/Head.php";


if(isset($_SESSION['userID'])){
    if($_SESSION['userType'] === "admin"){
        header("location: ../Admin/index.php");
    }
    else{
        header("location ../index.php");
    }
}

//Define login error variables
$loginError = null;
$loginRequest = null;


if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = strtolower(validateInput($_POST["username"]));
    $password = validateInput($_POST["password"]);


    if(strpos($username, '@') !== false){
        $loginError = "email";
    }
    else{
        $ldapSuccess = ldapAuthentication($username, $password);
        if($ldapSuccess){

            if(substr($username, 0, 1) == "c"){
                $username = substr($username, 1);
            }
            $usernameSend = encryptUsername($username);
            $userDetails = sqlFetch("SELECT * FROM Ambassadors WHERE universityID = '".$usernameSend."' LIMIT 1", "ASSOC");
        
            if(count($userDetails) == 1){
                $userDetails = $userDetails[0];

                //Create critical session variables
                $_SESSION["userID"] = $userDetails["universityID"];
                $_SESSION["accessLevel"] = 0;

                //Create additional session variables
                $_SESSION["surname"] = decrypt($userDetails["surname"]);
                $_SESSION["forename"] = decrypt($userDetails["forename"]);
                $_SESSION["givenName"] = decrypt($userDetails["givenName"]);
                $_SESSION["phoneNo"] = decrypt($userDetails["phoneNo"]);
                $_SESSION["programOfStudy"] = $userDetails["programOfStudy"];
                $_SESSION["yearOfStudy"] = $userDetails["yearOfStudy"];
                $_SESSION["driver"] = $userDetails["driver"];
                $_SESSION["tShirtSize"] = $userDetails["tShirtSize"];
                $_SESSION["training"] = json_decode($userDetails["trainingCompleted"]);
                $_SESSION["eventsAttended"] = $userDetails["eventsAttended"];
                $_SESSION["eventsMissed"] = $userDetails["eventsMissed"];
                $_SESSION["timesheets"] = $userDetails["timesheets"];

                if($userDetails["jobshopConfirmed"] != 1){
                    $_SESSION["jobshopPending"] = True;
                }

                $url = "http://".$_SERVER["HTTP_HOST"]."/PHP/Metrics/IncrementMetric.php";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0.5); 
                curl_setopt($ch, CURLOPT_TIMEOUT, 0.5);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "metricName=Ambassador Logins&errorLocation=Ambassador/Login");
                curl_exec($ch);
                curl_close($ch);


                //Redirect to homepage
                header("location: index.php");
                
            }
        }
    }
}
else{
    $loginRequest = "none";
}

?>

<div class="login-page">
    <h2>Ambassador Login</h2>
    <div class="login-form">
        <p id="loginError">
        <?php

        if($loginError == "email"){
            echo "You must use your student ID not your email address to log in";
        }
        elseif($loginRequest != "none"){
            echo "Incorrect details, please try again.";
        }
            
        ?>
        </p>
        <form method="POST">
            <label>Student ID</label><input name="username" type="text"><br/>
            <label>Password</label><input name="password" type="password"><br/>
            <div style='text-align: center;'><input type="submit" value="Log in"></div>
        </form>
        <p id="forgottenPassword"><a href='https://password.cardiff.ac.uk/public/ForgottenPassword'>Forgotten your password?</p>
    </div><!-- login-form-->
</div><!-- login-page -->

<?php

include "../PageComponents/Foot.php";

?>