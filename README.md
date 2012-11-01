/* Replaced /* Replaced /* Replaced Guzzle */ */ */, PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ and webservice framework
================================================

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ and framework for building RESTful web service /* Replaced /* Replaced /* Replaced client */ */ */s.

- Extremely powerful API provides all the power of cURL with a simple interface.
- Truly take advantage of HTTP/1.1 with persistent connections, connection pooling, and parallel requests.
- Service description DSL allows you build awesome web service /* Replaced /* Replaced /* Replaced client */ */ */s faster.
- Symfony2 event-based plugin system allows you to completely modify the behavior of a request.
- Includes a custom node.js webserver to test your /* Replaced /* Replaced /* Replaced client */ */ */s.
- Unit-tested with PHPUnit with 100% code coverage.

Getting started
---------------

- [Download the phar](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org//* Replaced /* Replaced /* Replaced guzzle */ */ */.phar) and include it in your project ([minimal phar](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org//* Replaced /* Replaced /* Replaced guzzle */ */ */-min.phar))
- Docs: [www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org](http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/)
- Forum: https://groups.google.com/forum/?hl=en#!forum//* Replaced /* Replaced /* Replaced guzzle */ */ */
- IRC: [#/* Replaced /* Replaced /* Replaced guzzle */ */ */php](irc://irc.freenode.net/#/* Replaced /* Replaced /* Replaced guzzle */ */ */php) channel on irc.freenode.net

### Installing via Composer

The recommended way to install /* Replaced /* Replaced /* Replaced Guzzle */ */ */ is through [Composer](http://getcomposer.org).

1. Add ``/* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */`` as a dependency in your project's ``composer.json`` file:

        {
            "require": {
                "/* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */": "*"
            }
        }

    Consider tightening your dependencies to a known version when deploying mission critical applications (e.g. ``2.8.*``).

2. Download and install Composer:

        curl -s http://getcomposer.org/installer | php

3. Install your dependencies:

        php composer.phar install

4. Require Composer's autoloader

    Composer also prepares an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process:

        require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).

Features
--------

- Supports GET, HEAD, POST, DELETE, PUT, PATCH, OPTIONS, and any custom verbs
- Allows full access to request and response headers
- Persistent connections are implicitly managed by /* Replaced /* Replaced /* Replaced Guzzle */ */ */, resulting in huge performance benefits
- [Send requests in parallel](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#send-http-requests-in-parallel)
- Cookie sessions can be maintained between requests using the [CookiePlugin](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#cookie-session-plugin)
- Allows custom [entity bodies](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#entity-bodies), including sending data from a PHP stream and downloading data to a PHP stream
- Responses can be cached and served from cache using the [caching forward proxy plugin](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#php-based-caching-forward-proxy)
- Failed requests can be retried using [truncated exponential backoff](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#truncated-exponential-backoff) with custom retry policies
- Entity bodies can be validated automatically using Content-MD5 headers and the [MD5 hash validator plugin](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#md5-hash-validator-plugin)
- All data sent over the wire can be logged using the [LogPlugin](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/tour/http.html#over-the-wire-logging)
- Subject/Observer signal slot system for unobtrusively [modifying request behavior](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/guide/http/creating_plugins.html)
- Supports all of the features of libcurl including authentication, compression, redirects, SSL, proxies, etc
- Web service /* Replaced /* Replaced /* Replaced client */ */ */ framework for building future-proof interfaces to web services
- Includes a [service description DSL](http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/guide/service/service_descriptions.html) for quickly building webservice /* Replaced /* Replaced /* Replaced client */ */ */s
- Full support for [URI templates](http://tools.ietf.org/html/rfc6570)
- Advanced batching functionality to efficiently send requests or commands in parallel with customizable batch sizes and transfer strategies

HTTP basics
-----------

```php
<?php

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;

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

try {
    $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client('http://www.myapi.com/api/v1');
    $responses = $/* Replaced /* Replaced /* Replaced client */ */ */->send(array(
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('users'),
        $/* Replaced /* Replaced /* Replaced client */ */ */->head('messages/123'),
        $/* Replaced /* Replaced /* Replaced client */ */ */->delete('orders/123')
    ));
} catch (/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\ExceptionCollection $e) {
    echo "The following requests encountered an exception: \n";
    foreach ($e as $exception) {
        echo $exception->getRequest() . "\n" . $exception->getMessage() . "\n";
    }
}
```

URI templates
-------------

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ supports the entire [URI templates RFC](http://tools.ietf.org/html/rfc6570).

```php
<?php

$/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client('http://www.myapi.com/api/v1', array(
    'path' => '/path/to',
    'a'    => 'hi',
    'data' => array(
        'foo'  => 'bar',
        'mesa' => 'jarjar'
    )
));

$request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.test.com{+path}{?a,data*}');
```

The generated request URL would become: ``http://www.test.com/path/to?a=hi&foo=bar&mesa=jarajar``

You can specify URI templates and an array of additional template variables to use when creating requests:

```php
<?php

$/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client('http://test.com', array(
    'a' => 'hi'
));

$request = $/* Replaced /* Replaced /* Replaced client */ */ */->get(array('/{?a,b}', array(
    'b' => 'there'
));
```

The resulting URL would become ``http://test.com?a=hi&b=there``

Unit testing
------------

[![Build Status](https://secure.travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */.png?branch=master)](http://travis-ci.org//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */)

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ uses PHPUnit for unit testing. In order to run the unit tests, you'll firt need
to install the dependencies of the project using Composer: `php composer.phar install --dev`.
You can then run the tests using `vendor/bin/phpunit` or `phing test` (if you have phing installed).
