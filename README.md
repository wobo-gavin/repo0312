/* Replaced /* Replaced /* Replaced Guzzle */ */ */, PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ and webservice framework
================================================

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ and framework for building RESTful web service /* Replaced /* Replaced /* Replaced client */ */ */s.

- Extremely powerful API provides all the power of cURL with a simple interface.
- Truly take advantage of HTTP/1.1 with persistent connections, connection pooling, and parallel requests.
- Service description DSL allows you build awesome web service /* Replaced /* Replaced /* Replaced client */ */ */s faster.
- Symfony2 event-based plugin system allows you to completely modify the behavior of a request.

### Quick example

```php
// Really simple using a static facade
/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\StaticClient::mount();
$response = /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get('http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org');

// More control using a /* Replaced /* Replaced /* Replaced client */ */ */ class
$/* Replaced /* Replaced /* Replaced client */ */ */ = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client('http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org');
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
$response = $request->send();
```

Getting started
---------------

- [Documentation](http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/)
- [Forum](https://groups.google.com/forum/?hl=en#!forum//* Replaced /* Replaced /* Replaced guzzle */ */ */)
- IRC: [#/* Replaced /* Replaced /* Replaced guzzle */ */ */php](irc://irc.freenode.net/#/* Replaced /* Replaced /* Replaced guzzle */ */ */php) channel on irc.freenode.net

### Installing via Composer

The recommended way to install /* Replaced /* Replaced /* Replaced Guzzle */ */ */ is through [Composer](http://getcomposer.org).

```bash
# Install Composer
curl -s http://getcomposer.org/installer | php

# Add /* Replaced /* Replaced /* Replaced Guzzle */ */ */ as a dependency
php composer.phar require /* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */:~3.6
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

### Installing via phar

[Download the phar](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org//* Replaced /* Replaced /* Replaced guzzle */ */ */.phar) and include it in your project
([minimal phar](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org//* Replaced /* Replaced /* Replaced guzzle */ */ */-min.phar))

Features
--------

- Supports GET, HEAD, POST, DELETE, PUT, PATCH, OPTIONS, and any other custom HTTP method
- Allows full access to request and response headers
- Persistent connections are implicitly managed by /* Replaced /* Replaced /* Replaced Guzzle */ */ */, resulting in huge performance benefits
- [Send requests in parallel](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#send-http-requests-in-parallel)
- Cookie sessions can be maintained between requests using the
  [CookiePlugin](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#cookie-session-plugin)
- Allows custom [entity bodies](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#entity-bodies), including sending data from a PHP
  stream and downloading data to a PHP stream
- Responses can be cached and served from cache using the
  [caching forward proxy plugin](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#php-based-caching-forward-proxy)
- Failed requests can be retried using
  [truncated exponential backoff](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#truncated-exponential-backoff) with custom retry
  policies
- Entity bodies can be validated automatically using Content-MD5 headers and the
  [MD5 hash validator plugin](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#md5-hash-validator-plugin)
- All data sent over the wire can be logged using the
  [LogPlugin](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#over-the-wire-logging)
- Subject/Observer signal slot system for unobtrusively
  [modifying request behavior](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/guide/http/creating_plugins.html)
- Supports all of the features of libcurl including authentication, compression, redirects, SSL, proxies, etc
- Web service /* Replaced /* Replaced /* Replaced client */ */ */ framework for building future-proof interfaces to web services
- Includes a [service description DSL](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/guide/service/service_descriptions.html) for quickly
  building webservice /* Replaced /* Replaced /* Replaced client */ */ */s
- Full support for [URI templates](http://tools.ietf.org/html/rfc6570)
- Advanced batching functionality to efficiently send requests or commands in parallel with customizable batch sizes
  and transfer strategies

HTTP basics
-----------

```php
<?php

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;

$/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.example.com/api/v1/key/{key}', [
    'key' => '***'
]);

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
$response = $/* Replaced /* Replaced /* Replaced client */ */ */->put('upload/text', [
    'X-Header' => 'My Header'
], 'body of the request')->send();

// Send a PUT request using the contents of a PHP stream as the body
// Send using an absolute URL (overrides the base URL)
$response = $/* Replaced /* Replaced /* Replaced client */ */ */->put('http://www.example.com/upload', [
    'X-Header' => 'My Header'
], fopen('http://www.test.com/', 'r'));

// Create a POST request with a file upload (notice the @ symbol):
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->post('http://localhost:8983/solr/update', null, [
    'custom_field' => 'my value',
    'file' => '@/path/to/documents.xml'
]);

// Create a POST request and add the POST files manually
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->post('http://localhost:8983/solr/update')
    ->addPostFiles(['file' => '/path/to/documents.xml']);

// Responses are objects
echo $response->getStatusCode() . ' ' . $response->getReasonPhrase() . "\n";

// Requests and responses can be cast to a string to show the raw HTTP message
echo $request . "\n\n" . $response;

// Create a request based on an HTTP message
$request = RequestFactory::fromMessage(
    "PUT / HTTP/1.1\r\n" .
    "Host: test.com:8081\r\n" .
    "Content-Type: text/plain" .
    "Transfer-Encoding: chunked\r\n" .
    "\r\n" .
    "this is the body"
);
```

Using the static /* Replaced /* Replaced /* Replaced client */ */ */ facade
------------------------------

You can use /* Replaced /* Replaced /* Replaced Guzzle */ */ */ through a static /* Replaced /* Replaced /* Replaced client */ */ */ to make it even easier to send simple HTTP requests.

```php
<?php

// Use the static /* Replaced /* Replaced /* Replaced client */ */ */ directly:
$response = /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\StaticClient::get('http://www.google.com');

// Or, mount the /* Replaced /* Replaced /* Replaced client */ */ */ to \/* Replaced /* Replaced /* Replaced Guzzle */ */ */ to make it easier to use
/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\StaticClient::mount();

$response = /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get('http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org');

// Custom options can be passed into requests created by the static /* Replaced /* Replaced /* Replaced client */ */ */
$response = /* Replaced /* Replaced /* Replaced Guzzle */ */ */::post('http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org', [
    'headers' => ['X-Foo' => 'Bar']
    'body'    => ['Foo' => 'Bar'],
    'query'   => ['Test => 123],
    'timeout' => 10,
    'debug'   => true,
    'save_to' => '/path/to/file.html'
]);
```

### Available request options:

* "headers": Associative array of headers
* "body": Body of a request, including an EntityBody, string, or array when sending POST requests. Setting a body for a
  GET request will set where the response body is downloaded.
* "save_to": String, fopen resource, or EntityBody object used to store the body of the response
* "allow_redirects": Set to false to disable redirects
* "auth": Basic auth array where [0] is the username, [1] is the password, and [2] (optional) is the type
* "query": Associative array of query string values to add to the request
* "cookies": Associative array of cookies
* "timeout": Float describing the timeout of the request in seconds
* "verify": Set to true to enable SSL cert validation (the default), false to disable, or supply the path to a CA
   bundle to enable verification using a custom certificate.
* "proxy": Specify an HTTP proxy (e.g. "http://username:password@192.168.16.1:10")
* "curl": Associative array of CURL options to add to the request
* "events": Associative array mapping event names to a closure or array of (priority, closure)
* "plugins": Array of plugins to add to the request
* "debug": Set to true to display all data sent over the wire
* "exceptions": Set to false to disable throwing exceptions on an HTTP level error (e.g. 404, 500, etc)

These options can also be used when creating request using a standard /* Replaced /* Replaced /* Replaced client */ */ */:

```php
$/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client();
// Create a request with a timeout of 10 seconds
$request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org', [], ['timeout' => 10]);
$response = $request->send();
```

Unit testing
------------

[![Build Status](https://secure.travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */.png?branch=master)](http://travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */)

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ uses PHPUnit for unit testing. In order to run the unit tests, you'll first need
to install the dependencies of the project using Composer: `php composer.phar install --dev`.
You can then run the tests using `vendor/bin/phpunit`.
