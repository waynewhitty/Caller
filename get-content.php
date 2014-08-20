<?php

/**
 * Example of how to get the content of a particular URL using a GET request.
 */



//Include the Caller.php file.
require 'lib/Caller.php';



//Initiate the Request object.
$request = new \Caller\Request();



/**
 * Get the content of a particular URL.
 */
$url = 'http://wikipedia.org';
$content = $request->get($url, true);
echo $content;
