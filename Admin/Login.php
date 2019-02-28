<?php

include "../PageComponents/Head.php";


if(isset($_SESSION['userID'])){
    if(isset($_SESSION['userType']) && $_SESSION['userType'] === "admin"){
        header("location: index.php");
    }
    else{
        header("location ../Ambassador/index.php");
    }
}


//Define error message variables;
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
            $usernameSend = encryptUsername(strtolower($username));
            $userDetails = sqlFetch("SELECT * FROM Admin WHERE adminID = '".$usernameSend."' LIMIT 1", "ASSOC");
    
            if(count($userDetails) == 1){
                $userDetails = $userDetails[0];

                //Create critical session variables
                $_SESSION["userID"] = $userDetails["adminID"];
                $_SESSION["userType"] = "admin";
                $_SESSION["accessLevel"] = $userDetails["accessLevel"];

                //Create additional session variables
                $_SESSION["givenName"] = decrypt($userDetails["givenName"]);
                $_SESSION["forename"] = decrypt($userDetails["forename"]);
                $_SESSION["surname"] = decrypt($userDetails["surname"]);
                $_SESSION["email"] = $userDetails["email"];

                //Add leadAmbassdor session if user also exists in Ambassadors table
                $leadAmbassador = sqlFetch($query = "SELECT * FROM Ambassadors WHERE universityID = '".$userDetails["adminID"]."' LIMIT 1", "ASSOC");

                if(count($leadAmbassador) == 1){
                    $_SESSION["leadAmbassador"] = True;
                }
                else{
                    $_SESSION["leadAmbassador"] = False;
                }


                $url = "http://".$_SERVER["HTTP_HOST"]."/PHP/Metrics/IncrementMetric.php";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0.5); 
                curl_setopt($ch, CURLOPT_TIMEOUT, 0.5);
                curl_setopt($ch, CURLOPT_POST, 1);

                if($userDetails["accessLevel"] == 1){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "metricName=Lead Ambassador Logins&errorLocation=Admin/Login");
                    curl_exec($ch);
                    curl_close($ch);
                }
                else if($userDetails["accessLevel"] == 2){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "metricName=Officer Ambassador Logins&errorLocation=Admin/Login");
                    curl_exec($ch);
                    curl_close($ch);
                }
                else if($userDetails["accessLevel"] < 5){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "metricName=Staff Logins&errorLocation=Admin/Login");
                    curl_exec($ch);
                    curl_close($ch);
                }

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
    <h2>Admin Login</h2>
    <div class="login-form">
        <p id="loginError">
        <?php
        
        if($loginError == "email"){
            echo "You must use your username not your email address to log in";
        }
        elseif($loginRequest != "none"){
            echo "Incorrect details, please try again.";
        }
        
        ?>
        </p>
        <form method="POST">
            <label>Username</label><input name="username" type="text"><br/>
            <label>Password</label><input name="password" type="password"><br/>
            <div style='text-align: center;'><input type="submit" value="Log in"></div>
    </form>
    <p id="forgottenPassword"><a href='https://password.cardiff.ac.uk/public/ForgottenPassword'>Forgotten your password?</a></p>
    </div><!-- login-form-->
</div><!-- login-page -->

<?php

include "../PageComponents/Foot.php";

?>