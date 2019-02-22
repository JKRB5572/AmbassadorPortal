<?php

function validateInput($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


function hasValue($value){
    if($value){
        return "'".$value."'";
    }
    else{
        return "NULL";
    }
}


function isNull($value){
    if($value){
        return " = '".$value."'";
    }
    else{
        return " IS NULL";
    }
}


?>