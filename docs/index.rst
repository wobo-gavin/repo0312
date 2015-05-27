.. title:: /* Replaced /* Replaced /* Replaced Guzzle */ */ */, PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */

====================
/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Documentation
====================

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ that makes it easy to send HTTP requests and
trivial to integrate with web services.

- Simple interface for building query strings, POST requests, streaming large
  uploads, streaming large downloads, using HTTP cookies, uploading JSON data,
  etc...
- Can send both synchronous and asynchronous requests using the same interface.
- Uses PSR-7 interfaces for requests, responses, and streams. This allows you
  to utilize other PSR-7 compatible libraries with /* Replaced /* Replaced /* Replaced Guzzle */ */ */.
- Abstracts away the underlying HTTP transport, allowing you to write
  environment and transport agnostic code; i.e., no hard dependency on cURL,
  PHP streams, sockets, or non-blocking event loops.
- Middleware system allows you to augment and compose /* Replaced /* Replaced /* Replaced client */ */ */ behavior.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client();
    $res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('https://api.github.com/user', ['auth' =>  ['user', 'pass']]);
    echo $res->getStatusCode();
    // "200"
    echo $res->getHeader('content-type');
    // 'application/json; charset=utf8'
    echo $res->getBody();
    // {"type":"User"...'

    // Send an asynchronous request.
    $request = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request('GET', 'http://httpbin.org');
    $promise = $/* Replaced /* Replaced /* Replaced client */ */ */->sendAsync($request)->then(function ($response) {
        echo 'I completed! ' . $response;
    });
    $promise->wait();


User Guide
==========

.. toctree::
    :maxdepth: 3

    overview
    quickstart
    request-options
    psr7
    handlers-and-middleware
    testing
    faq
