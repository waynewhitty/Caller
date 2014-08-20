<?php

/**
 * PHP wrapper class for cURL.
 */

namespace Caller;

class Request{
    
    
    /**
     * cURL handler.
     * 
     * @var resource 
     */
    public $curlHandler;
    
    
    
    
    
    
    /**
     * User Agent to use in requests.
     * 
     * @var string 
     */
    protected $userAgent = 'Caller - PHP';
    
    
    
    
    
    
    /**
     * The HTTP status code of the last request.
     * 
     * @var int 
     */
    protected $lastStatusCode;
    
    
    
    
    
    
    /**
     * Associative of HTTP status codes and their textual meanings.
     * 
     * @var array 
     */
    public $httpCodes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', //An April Fools joke from 1998 :)
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    );
    
    
    
    
    
    
    /**
     * Checks if cURL is enabled and sets a user agent.
     * 
     * @param string|null $userAgent User Agent to use for requests.
     * @throws RequestException If cURL is not enabled.
     */
    public function __construct($userAgent = null) {
        if(!function_exists('curl_init')){
            throw new RequestException('cURL not enabled');
        }
        if(!is_null($userAgent)){
            $this->userAgent = $userAgent;
        }
    }
    
    
    
    
    
    
    /**
     * Get the HTTP status code of a given URL. Performs a HEAD request.
     * 
     * @param string $url The URL in question.
     * @return int HTTP status code.
     * @throws RequestException If URL is invalid or request fails.
     */
    public function getHTTPStatus($url){
        
        //Make sure that the URL is valid.
        if(!$this->isValidUrl($url)){
            throw new RequestException('Invalid URL: ' . var_export($url, true));
        }
        
        //Perform the HEAD request.
        $this->curlHandler = curl_init($url);
        curl_setopt($this->curlHandler, CURLOPT_NOBODY, true);
        curl_setopt($this->curlHandler, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($this->curlHandler);
        
        //Check to see if the request failed.
        if(curl_errno($this->curlHandler)){
            throw new RequestException(curl_error($this->curlHandler));
        }
        
        //Get the HTTP status code.
        $statusCode = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);
        $this->lastStatusCode = $statusCode;
        
        //Close handler and return.
        curl_close($this->curlHandler);
        return $statusCode;
        
    }
    
    
    
    
    
    
    
    /**
     * Download a file.
     * 
     * @param string $fileUrl The URL of the file.
     * @param string $saveTo Path to save the file to.
     * @param int $timeout The maximum number of seconds to allow cURL functions to execute.
     * Default is 5.
     * @return boolean TRUE on success.
     * @throws RequestException If file handler could not be opened, if URL is invalid or 
     * if request fails.
     */
    public function downloadFile($fileUrl, $saveTo, $timeout = 20){
        
        //Make sure that the URL is valid.
        if(!$this->isValidUrl($fileUrl)){
            throw new RequestException('Invalid URL: ' . var_export($fileUrl, true));
        }
        
        //Open file handler.
        $fp = fopen($saveTo, 'w+');
        
        //If file handler could be opened, throw an exception.
        if($fp === false){
            throw new RequestException('Could not open: ' . $saveTo);
        }
        
        //Perform the GET request.
        $this->curlHandler = curl_init($fileUrl);
        curl_setopt($this->curlHandler, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($this->curlHandler, CURLOPT_FILE, $fp);
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlHandler, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($this->curlHandler, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($this->curlHandler);
        
        //Check to see if the request failed.
        if(curl_errno($this->curlHandler)){
            throw new RequestException(curl_error($this->curlHandler));
        }
        
        //Get the HTTP status code.
        $statusCode = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);
        $this->lastStatusCode = $statusCode;
        
        //Close handler and return.
        curl_close($this->curlHandler);
        return true;
    }
    
    
    
    
    
    
    /**
     * Performs a GET request. GET content from a particular URL.
     * 
     * @param string $url The URL to perform a GET request on.
     * @param boolean $followRedirects TRUE by default. Set to FALSE if you do not
     * want to follow redirects.
     * @param int $timeout The maximum number of seconds to allow cURL functions to execute.
     * Default is 5.
     * @return string The output / content.
     * @throws RequestException If URL is invalid or request failed.
     */
    public function get($url, $followRedirects = true, $timeout = 5){
        
        //Make sure that the URL is valid.
        if(!$this->isValidUrl($url)){
            throw new RequestException('Invalid URL: ' . var_export($url, true));
        }
        
        //Perform the GET request.
        $this->curlHandler = curl_init($url);
        curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curlHandler, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($this->curlHandler, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlHandler, CURLOPT_FOLLOWLOCATION, $followRedirects);
        $response = curl_exec($this->curlHandler);
        
        //Check to see if the request failed.
        if(curl_errno($this->curlHandler)){
            throw new RequestException(curl_error($this->curlHandler));
        }
        
        //Get the HTTP status code.
        $statusCode = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);
        $this->lastStatusCode = $statusCode;
        
        //Close handler and return.
        curl_close($this->curlHandler);
        return $response;
        
    }
    
    
    
    
    
    
    /**
     * Performs a POST request. Send POST data to a particular URL.
     * 
     * @param type $url The URL to perform a POST request on.
     * @param array $postFields Associative array of POST fields: 
     * array('email' => 'test@test.com', 'name' => 'Wayne')
     * @param boolean $followRedirects TRUE by default. Set to FALSE if you do not
     * want to follow redirects.
     * @param int $timeout The maximum number of seconds to allow cURL functions to execute.
     * Default is 5.
     * @return string The output / content.
     * @throws RequestException If URL is invalid or request fails.
     */
    public function post($url, $postFields = array(), $followRedirects = true, $timeout = 5){
        
        //Make sure that the URL is valid.
        if(!$this->isValidUrl($url)){
            throw new RequestException('Invalid URL: ' . var_export($url, true));
        }
        
        //Perform the POST request.
        $this->curlHandler = curl_init($url);
        curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curlHandler, CURLOPT_POST, true);
        curl_setopt($this->curlHandler, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($this->curlHandler, CURLOPT_FOLLOWLOCATION, $followRedirects);
        curl_setopt($this->curlHandler, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlHandler, CURLOPT_USERAGENT, $this->userAgent);
        $response = curl_exec($this->curlHandler);
        
        //Check to see if the request failed.
        if(curl_errno($this->curlHandler)){
            throw new RequestException(curl_error($this->curlHandler));
        }
        
        //Get the HTTP status code.
        $statusCode = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);
        $this->lastStatusCode = $statusCode;
        
        //Close handler and return.
        curl_close($this->curlHandler);
        return $response;        
        
    }
    
    
    
    
    
    
    /**
     * Get the HTTP status code of the last request.
     * 
     * @return int
     */
    public function getLastStatusCode(){
        return $this->lastStatusCode;
    }
    
    
    
    
    
    
    /**
     * Get the textual meaning of a given HTTP status code.
     * 
     * @param int $statusCode The HTTP status code in question.
     * @return string Textual meaning.
     */
    public function getStatusCodeMeaning($statusCode){
        if(!array_key_exists($statusCode, $this->httpCodes)){
            return 'Unknown Status Code';
        }
        return $this->httpCodes[$statusCode];
    }
    
    
    
    
    
    
    /**
     * Check to see if a URL is valid or not.
     * 
     * @param string $url The URL to validate.
     * @return boolean TRUE if valid. FALSE if invalid.
     */
    public function isValidUrl($url){
        if(!is_string($url)){
            return false;
        }
        if(filter_var($url, FILTER_VALIDATE_URL) === false){
            return false;
        }
        return true;
    }
    
    
    
    
}