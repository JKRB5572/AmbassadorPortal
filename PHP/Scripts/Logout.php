<?php

require_once "/var/www/html/PHP/Config.php";

$_SESSION = array();
session_destroy();
header("location: http://".$_SERVER[HTTP_HOST]);
exit;

?>