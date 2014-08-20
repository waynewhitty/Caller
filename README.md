Caller
======

A very basic cURL wrapper for PHP. See files for some basic examples.

Including
======

```php
//Include the Caller.php file.
require 'lib/Caller.php';

//Initiate the Request object.
$request = new \Caller\Request();
```

GET Request
======

```php
//Get the content of a particular URL.

$url = 'http://wikipedia.org';
$content = $request->get($url, true);
echo $content;
```

POST Request
======

```php
//Perform POST request.

$url = 'http://wikipedia.org';

$postFields = array(
   'name' => 'Wayne',
   'fieldname2' => 'Test',
   'email_field' => 'test@test.com' 
);

$response = $request->post($url, $postFields);

//Print response output
echo $response;
```

HTTP Status Code
======

```php
//Get the HTTP status code of a particular URL.
$url = 'http://wikipedia.org';
$statusCode = $request->getHTTPStatus($url);
$statusCodeText = $request->getStatusCodeMeaning($statusCode);

//Print the result.
echo "URL <b>$url</b> returned a status code of:<br>";
echo "$statusCode $statusCodeText";
```
