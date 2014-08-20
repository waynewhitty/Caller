Caller
======

A very basic cURL wrapper for PHP.
Will carry out some of the more popular cURL actions.

Including
======
Including the Caller library.

```php
//Include the Caller.php file.
require 'lib/Caller.php';

//Initiate the Request object.
$request = new \Caller\Request();
```

GET Request
======
Get the content of a particular web page via a GET request.

```php
//Get the content of a particular URL.
$url = 'http://wikipedia.org';
$content = $request->get($url, true);
echo $content;
```

POST Request
======
Send a POST request to a given URL.

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
Get the HTTP status code of a particular resource. This will send a HEAD request.

```php
//Get the HTTP status code of a particular URL.
$url = 'http://wikipedia.org';
$statusCode = $request->getHTTPStatus($url);
$statusCodeText = $request->getStatusCodeMeaning($statusCode);

//Print the result.
echo "URL <b>$url</b> returned a status code of:<br>";
echo "$statusCode $statusCodeText";
```

Download File
======
Downloading a file to a given location.

```php
//Download the file.
$googleLogoUrl = 'http://upload.wikimedia.org/wikipedia/commons/5/51/Google.png';
$savePath = 'google-logo.png';
$result = $request->downloadFile($googleLogoUrl, $savePath);
```

Exceptions
======
Handling exceptions.

```php
//Bad host example
$url = 'http://blahblahblah12345.org';
try{
    $content = $request->get($url);
} catch(Caller\RequestException $e){
    echo $e->getMessage();
}
```

Custom User Agent
======
Setting a custom user agent.

```php
$userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';
$request = new \Caller\Request($userAgent);
```

Previous HTTP Status Code
======
Get the HTTP status code of a previous request.

```php
$url = 'http://wikipedia.org';
$content = $request->get($url);
$statusCode = $request->getLastStatusCode();
echo "HTTP Status: $statusCode<br>";
```
