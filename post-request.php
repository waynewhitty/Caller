<?php

/**
 * An example of a POST request.
 */



/**
 * Include the Caller.php file.
 */
require 'lib/Caller.php';



/**
 * Initiate the Request object.
 */
$request = new \Caller\Request();



/**
 * Perform POST request.
 */
$url = 'http://wikipedia.org';

$postFields = array(
   'name' => 'Wayne',
   'fieldname2' => 'Test',
   'email_field' => 'test@test.com' 
);

$response = $request->post($url, $postFields);



//Print response output
echo $response;

