<?php


function generateIV(){
    $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $returnString = "";
    for ($i = 0; $i < 16; $i++) {
        $returnString .= $alphabet[rand(0, strlen($alphabet) - 1)];
    }
    return $returnString;
}

function encrypt($data){
    if($data != ""){
        $IV = generateIV();
        $encryption = base64_encode(openssl_encrypt($data, "AES-192-CTR", $_ENV["ENCRYPTION_KEY"], 0, $IV));
        return $encryption."---".$IV;
    }
    else{
        return "";
    }
}

function decrypt($data){
    list($text, $IV) = array_pad(explode("---", $data), 2, null);
    return openssl_decrypt(base64_decode($text), "AES-192-CTR", $_ENV["ENCRYPTION_KEY"], 0, $IV);
}

function encryptUsername($data){
    return base64_encode(openssl_encrypt($data, "AES-192-CTR", $_ENV["ENCRYPTION_KEY_USERNAME"], 0, $_ENV["ENCRYPTION_IV_USERNAME"]));
}

function decryptUsername($date){
    return base64_decode(openssl_encrypt($data, "AES-192-CTR", $_ENV["ENCRYPTION_KEY_USERNAME"], 0, $_ENV["ENCRYPTION_IV_USERNAME"]));
}

?>