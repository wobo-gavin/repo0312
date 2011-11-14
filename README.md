/* Replaced /* Replaced /* Replaced Guzzle */ */ */, PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ and webservice framework
===============================================

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a game changer in the world of PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */s. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ allows you to truly reap the benefits of the HTTP/1.1 spec. No other library provides persistent connection management or makes it easier to send requests in parallel.</p>

In addition to taking the pain out of HTTP, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ provides a lightweight framework for creating web service /* Replaced /* Replaced /* Replaced client */ */ */s.  Most web service /* Replaced /* Replaced /* Replaced client */ */ */s follow a specific pattern: create a /* Replaced /* Replaced /* Replaced client */ */ */ class, create methods for each action, create and execute a cURL handle, parse the response, implement error handling, and return the result. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ takes the redundancy out of this process and gives you the tools you need to quickly build a web service /* Replaced /* Replaced /* Replaced client */ */ */.

Start <strong>truly</strong> consuming HTTP with /* Replaced /* Replaced /* Replaced Guzzle */ */ */.

- Download the phar to get started: http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/phar
- Docs: [www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org](http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/)
- Forum: https://groups.google.com/forum/?hl=en#!forum//* Replaced /* Replaced /* Replaced guzzle */ */ */

[![Build Status](https://secure.travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */.png)](http://travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */)

Features
--------

- Supports GET, HEAD, POST, DELETE, PUT, and OPTIONS
- Allows full access to request and response headers
- Persistent connections are implicitly managed by /* Replaced /* Replaced /* Replaced Guzzle */ */ */, resulting in huge performance benefits
- Send requests in parallel
- Cookie sessions can be maintained between requests using the CookiePlugin
- Allows custom entity bodies to be sent in PUT and POST requests, including sending data from a PHP stream
- Responses can be cached and served from cache using the caching reverse proxy plugin
- Failed requests can be retried using truncated exponential backoff
- Entity bodies can be validated automatically using Content-MD5 headers
- All data sent over the wire can be logged using the LogPlugin
- Automatically requests compressed data and automatically decompresses data
- Subject/Observer signal slot system for unobtrusively modifying request behavior
- Supports all of the features of libcurl including authentication, redirects, SSL, proxies, etc
- Web service /* Replaced /* Replaced /* Replaced client */ */ */ framework for building future-proof interfaces to web services

HTTP basics
-----------

```php
<?php

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

$/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.example.com/api/v1/key/{{key}}', array(
    'key' => '***'
));

// Issue a path using a relative URL to the /* Replaced /* Replaced /* Replaced client */ */ */'s base URL
// Sends to http://www.example.com/api/v1/key/***/users
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('users');
$response = $request->send();

// Relative URL that overwrites the path of the base URL
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/test/123.php?a=b');

// Issue a head request on the base URL
$response = $/* Replaced /* Replaced /* Replaced client */ */ */->head()->send();
// Delete user 123
$response = $/* Replaced /* Replaced /* Replaced client */ */ */->delete('users/123')->send();

// Send a PUT request with custom headers
$response = $/* Replaced /* Replaced /* Replaced client */ */ */->put('upload/text', array(
    'X-Header' => 'My Header'
), 'body of the request')->send();

// Send a PUT request using the contents of a PHP stream as the body
// Send using an absolute URL (overrides the base URL)
$response = $/* Replaced /* Replaced /* Replaced client */ */ */->put('http://www.example.com/upload', array(
    'X-Header' => 'My Header'
), fopen('http://www.test.com/', 'r'));

// Create a POST request with a file upload (notice the @ symbol):
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->post('http://localhost:8983/solr/update', null, array (
    'custom_field' => 'my value',
    'file' => '@/path/to/documents.xml'
));

// Create a POST request and add the POST files manually
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->post('http://localhost:8983/solr/update')
    ->addPostFiles(array(
        'file' => '/path/to/documents.xml'
    ));

// Responses are objects
echo $response->getStatusCode() . ' ' . $response->getReasonPhrase() . "\n";

// Requests and responses can be cast to a string to show the raw HTTP message
echo $request . "\n\n" . $response;

// Create a request based on an HTTP message
$request = RequestFactory::fromMessage(
    "PUT / HTTP/1.1\r\n" .
    "Host: test.com:8081\r\n" .
    "Content-Type: text/plain"
    "Transfer-Encoding: chunked\r\n" .
    "\r\n" .
    "this is the body"
);
```

Send requests in parallel
-------------------------

```php
<?php

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

try {
    $responses = $/* Replaced /* Replaced /* Replaced client */ */ */->batch(array(
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.google.com/'),
        $/* Replaced /* Replaced /* Replaced client */ */ */->head('http://www.google.com/'),
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('https://www.github.com/')
    ));
} catch (PoolRequestException $e) {
    echo "The following requests encountered an exception: \n";
    foreach ($e as $exception) {
        echo $exception->getRequest() . "\n" . $exception->getMessage() . "\n";
    }
}
```