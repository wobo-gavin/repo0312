.. title:: /* Replaced /* Replaced /* Replaced Guzzle */ */ */ | PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ and framework for consuming RESTful web services

======
/* Replaced /* Replaced /* Replaced Guzzle */ */ */
======

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ that makes it easy to send HTTP requests and super
simple to integrate with web services.

- Manages things like persistent connections, represents query strings as
  collections, simplifies sending streaming POST requests with fields and
  files, and abstracts away the underlying HTTP transport layer.
- Can send both synchronous and asynchronous requests using the same interface
  without requiring a dependency on an event loop.
- Pluggable HTTP adapters allows /* Replaced /* Replaced /* Replaced Guzzle */ */ */ to integrate with any method you choose
  for sending HTTP requests over the wire (e.g., cURL, sockets, PHP's stream
  wrapper, non-blocking event loops like `React <http://reactphp.org/>`_, etc.).
- /* Replaced /* Replaced /* Replaced Guzzle */ */ */ makes it so that you no longer need to fool around with cURL options,
  stream contexts, or sockets.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client();
    $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org');
    $res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('https://api.github.com/user', ['auth' =>  ['user', 'pass']]);
    echo $res->getStatusCode();
    // "200"
    echo $res->getHeader('content-type');
    // 'application/json; charset=utf8'
    echo $res->getBody();
    // {"type":"User"...'
    var_export($res->json());
    // Outputs the JSON decoded data

    // Send an asynchronous request.
    $req = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httpbin.org', ['future' => true]);
    $/* Replaced /* Replaced /* Replaced client */ */ */->send($req)->then(function ($response) {
        echo 'I completed! ' . $response;
    });

User guide
----------

.. toctree::
    :maxdepth: 2

    overview
    quickstart
    /* Replaced /* Replaced /* Replaced client */ */ */s
    http-messages
    events
    streams
    adapters
    testing
    faq

HTTP Components
---------------

There are a number of optional libraries you can use along with /* Replaced /* Replaced /* Replaced Guzzle */ */ */'s HTTP
layer to add capabilities to the /* Replaced /* Replaced /* Replaced client */ */ */.

`Log Subscriber <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *//log-subscriber>`_
    Logs HTTP requests and responses sent over the wire using customizable
    log message templates.

`OAuth Subscriber <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *//oauth-subscriber>`_
    Signs requests using OAuth 1.0.

`Cache Subscriber <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *//cache-subscriber>`_
    Implements a private transparent proxy cache that caches HTTP responses.

`Retry Subscriber <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *//retry-subscriber>`_
    Retries failed requests using customizable retry strategies (e.g., retry
    based on response status code, cURL error codes, etc.)

`Message Integrity Subscriber <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *//message-integrity-subscriber>`_
    Verifies the message integrity of HTTP responses using customizable
    validators. This plugin can be used, for example, to verify the Content-MD5
    headers of responses.

Service Description Commands
----------------------------

You can use the **/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Command** library to encapsulate interaction with a
web service using command objects. Building on top of /* Replaced /* Replaced /* Replaced Guzzle */ */ */'s command
abstraction allows you to easily implement things like service description that
can be used to serialize requests and parse responses using a meta-description
of a web service.

`/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Command <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *//command>`_
    Provides the foundational elements used to build high-level, command based,
    web service /* Replaced /* Replaced /* Replaced client */ */ */s with /* Replaced /* Replaced /* Replaced Guzzle */ */ */.

`/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Services <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-services>`_
    Provides an implementation of the */* Replaced /* Replaced /* Replaced Guzzle */ */ */ Command* library that uses
    /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service descriptions to describe web services, serialize requests,
    and parse responses into easy to use model structures.
