<?php

require_once "../PHP/Config.php";
require_once "../PHP/CoreFunctions.php";

if(!$isDeveloperPane){
    header("location: index.php");
}

?>

<h4>Session Variables</h4>

<?php

if (!is_writable(session_save_path())) {
    echo '<p style="color: red;" Session path is not writable: "'.session_save_path().'"</p>'; 
}
else{
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
}

?>