<?php

/**
 * Example of how to download a file.
 */



//Include the Caller.php file.
require 'lib/Caller.php';



//Initiate the Request object.
$request = new \Caller\Request();



//Download the file.
$googleLogoUrl = 'http://upload.wikimedia.org/wikipedia/commons/5/51/Google.png';
$savePath = 'google-logo.png';

$result = $request->downloadFile($googleLogoUrl, $savePath);
if($result === true){
    $statusCode = $request->getLastStatusCode();
    if($statusCode == 200){
        echo 'Downloaded!';
    } else{
        echo "$statusCode status code returned.";
    }
}