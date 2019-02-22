<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";

if(!$isDeveloperPane){
    header("location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(validateInput($_POST["action"]) == "encrypt"){
        $type = validateInput($_POST["type"]);
        $value = validateInput($_POST["value"]);
    }
}


?>


<form method="POST">
    <input name="action" value="encrypt" style="display: none">
    <input name="value" type="text" style="width: 60%;"><br/>
    <div style="padding-top: 10px;">
        <div style="padding-top: 10px; float: left;">
            <input name="type" type="radio" value="E" default> Encrypt&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="type" type="radio" value="EU"> Encrypt Username&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="type" type="radio" value="D"> Decrypt
        </div>
        <div style="width: 73.61px; float: right;">
		    <button class="client-action">Run</button>
	    </div>
    </div>
</form>
<br/><br/>
<p>
    <?php
    
    if(isset($type)){
        if($type === "E"){
            echo "ENCRYPTED TEXT: <span style='color: purple;'>".encrypt($value)."</span>";
        }

        else if($type === "D"){
            echo "DECRYPTED TEXT: <span style='color: purple;'>".decrypt($value)."</span>";
        }

        else if($type === "EU"){
            echo "ENCRYPTED USERNAME: <span style='color: purple;'>".encryptUsername($value)."</span>";
        }
    }

    ?>
</p>