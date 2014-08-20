<?php

/**
 * Other examples
 */

//Include the Caller.php file.
require 'lib/Caller.php';





/**
 * Setting a custom user agent.
 */
$userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
$request = new \Caller\Request($userAgent);





/**
 * Checking the status code of a previous request.
 */
$url = 'http://wikipedia.org';
$content = $request->get($url);
$statusCode = $request->getLastStatusCode();
echo "HTTP Status: $statusCode<br>";





/**
 * Catching exceptions.
 */
$url = 'http://blahblahblah12345.org';
try{
    $content = $request->get($url);
    echo $content;
} catch(Caller\RequestException $e){
    echo $e->getMessage();
}
