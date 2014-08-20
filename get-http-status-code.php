<?php

/**
 * Example of how to get the HTTP status code of a particular URL.
 */



//Include the Caller.php file.
require 'lib/Caller.php';



//Initiate the Request object.
$request = new \Caller\Request();



//Get the HTTP status code of a particular URL.
$url = 'http://wikipedia.org';
$statusCode = $request->getHTTPStatus($url);
$statusCodeText = $request->getStatusCodeMeaning($statusCode);



//Print the result.
echo "URL <b>$url</b> returned a status code of:<br>";
echo "$statusCode $statusCodeText";