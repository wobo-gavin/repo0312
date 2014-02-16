=======
Clients
=======

Clients are used to create requests, create transactions, send requests
through an HTTP adapter, and return a response. You can add default request
options to a /* Replaced /* Replaced /* Replaced client */ */ */ that are applied to every request (e.g., default headers,
default query string parameters, etc), and you can add event listeners and
subscribers to every request created by a /* Replaced /* Replaced /* Replaced client */ */ */.

Creating a /* Replaced /* Replaced /* Replaced client */ */ */
=================

The constructor of a /* Replaced /* Replaced /* Replaced client */ */ */ accepts an associative array of configuration
options.

base_url
    Configures a base URL for the /* Replaced /* Replaced /* Replaced client */ */ */ so that requests created
    using a relative URL are combined with the ``base_url`` of the /* Replaced /* Replaced /* Replaced client */ */ */
    according to section `5.2 of RFC 3986 <http://tools.ietf.org/html/rfc3986#section-5.2>`_.

    .. code-block:: php

        // Create a /* Replaced /* Replaced /* Replaced client */ */ */ with a base URL
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client(['base_url' => 'https://github.com']);
        // Send a request to https://github.com/notifications
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/notifications');

    `Absolute URLs <http://tools.ietf.org/html/rfc3986#section-4.3>`_ sent
    through a /* Replaced /* Replaced /* Replaced client */ */ */ will not use the base URL of the /* Replaced /* Replaced /* Replaced client */ */ */.

adapter
    Configures the HTTP adapter (``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\AdapterInterface``) used
    to transfer the HTTP requests of a /* Replaced /* Replaced /* Replaced client */ */ */. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will, by default, utilize
    a stacked adapter that chooses the best adapter to use based on the provided
    request options and based on the extensions available in the environment. If
    cURL is installed, it will be used as the default adapter. However, if a
    request has the ``stream`` request option, the PHP stream wrapper adapter
    will be used (assuming ``allow_url_fopen`` is enabled in your PHP
    environment).

parallel_adapter
    Just like the ``adapter`` option, you can choose to specify an adapter
    that is used to send requests in parallel
    (``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\ParallelAdapterInterface``). /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will by default
    use cURL to send requests in parallel, but if cURL is not available it will
    use the PHP stream wrapper and simply send requests serially.

message_factory
    Specifies the factory used to create HTTP requests and responses
    (``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface``).

defaults
    Specified an associative array of request options that are applied to every
    request created by the /* Replaced /* Replaced /* Replaced client */ */ */. This allows you to, for example, specifies
    an array of headers that are sent with every request.

Here's an example of creating a /* Replaced /* Replaced /* Replaced client */ */ */ with various options, including using
a mock adapter that just returns the result of a callable function and a
base URL that is a URI template with parameters.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
        ['https://api.twitter.com/{version}', ['version' => 'v1.1']],
        'defaults' => [
            'headers' => ['Foo' => 'Bar'],
            'query'   => ['testing' => '123'],
            'auth'    => ['username', 'password'],
            'proxy'   => 'tcp://localhost:80'
        ]
    ]);

Sending Requests
================

Requests can be created using various methods of a /* Replaced /* Replaced /* Replaced client */ */ */. You can create
**and** send requests using one of the following methods:

- ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client::get``: Sends a GET request.
- ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client::head``: Sends a HEAD request
- ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client::post``: Sends a POST request
- ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client::put``: Sends a PUT request
- ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client::delete``: Sends a DELETE request
- ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client::options``: Sends an OPTIONS request

Each of the above methods accepts a URL as the first argument and an optional
associative array of :ref:`request-options` as the second argument.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client();

    $/* Replaced /* Replaced /* Replaced client */ */ */->put('http://httpbin.org', [
        'headers' => ['X-Foo' => 'Bar'],
        'body' => 'this is the body!',
        'save_to' => '/path/to/local/file',
        'allow_redirects' => false,
        'timeout' => 5
    ]);

Error Handling
--------------

Errors can be encountered during a transfer. When a recoverable error is
encountered while calling the ``send()`` method of a /* Replaced /* Replaced /* Replaced client */ */ */, a
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException`` is thrown. If the ``exceptions``
request option is not disabled, then exceptions are thrown for HTTP protocol
errors as well: ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ClientErrorResponseException`` for
400 level HTTP responses and ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ServerException`` for
500 level responses, both of which extend from
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\BadResponseException``.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();

    try {
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httpbin.org');
    } catch (RequestException $e) {
        echo $e->getRequest() . "\n";
        if ($e->hasResponse()) {
            echo $e->getResponse() . "\n";
        }
    }

A ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException`` always contains a
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface`` object that can be accessed using the
exception's ``getRequest()`` method. In the event of a networking error, no
response will be received. You can check if a ``RequestException`` has a
response using the ``hasResponse()`` method. If the exception has a response,
then you can access the ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface`` using the
``getResponse()`` method of the exception.

Creating Requests
-----------------

You can create a request without sending it. This is useful for building up
requests over time or sending requests in parallel.

.. code-block:: php

    $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httpbin.org', [
        'headers' => ['X-Foo' => 'Bar']
    ]);

    // Modify the request as needed
    $request->setHeader('Baz', 'bar');

After creating a request, you can send it with the /* Replaced /* Replaced /* Replaced client */ */ */'s ``send()`` method.

.. code-block:: php

    $response = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);

Sending Requests in Parallel
============================

You can send requests in parallel using a /* Replaced /* Replaced /* Replaced client */ */ */ object's ``sendAll()`` method.
The ``sendAll()`` method accepts an array or ``\Iterator`` that contains
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface`` objects. In addition to providing the
requests to send, you can also specify an associative array of options that
will affect the transfer.

.. code-block:: php

    $requests = [
        $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httpbin.org'),
        $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('DELETE', 'http://httpbin.org/delete'),
        $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', 'http://httpbin.org/put', ['body' => 'test'])
    ];

    $/* Replaced /* Replaced /* Replaced client */ */ */->sendAll($requests);

You can work with the responses for each request as the are received using the
events emitted from a request. Here we are using the ``complete`` event and
printing out each request URL and response body.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;

    $/* Replaced /* Replaced /* Replaced client */ */ */->sendAll($requests, [
        'complete' => function (CompleteEvent $event) {
            echo 'Completed request to ' . $event->getRequest()->getUrl() . "\n";
            echo 'Response: ' . $event->getResponse()->getBody() . "\n\n";
        }
    ]);

Asynchronous Error Handling
---------------------------

You can handle errors when transferring requests in parallel using the event
system.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;

    $/* Replaced /* Replaced /* Replaced client */ */ */->sendAll($requests, [
        'error' => function (ErrorEvent $event) {
            echo 'Request failed: ' . $event->getRequest()->getUrl() . "\n"
            echo $event->getException();
        }
    ]);

The ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent`` event object is emitted when an error
occurs during a transfer. With this event, you have access to the request that
was sent, the response that was received (if one was received), access to
transfer statistics, and the ability to intercept the exception with a
different ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface`` object. See :doc:`events`
for more information.

Handling Errors After Transferring
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Here we are adding each failed request to an array that we can use to process
errors later.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;

    $errors = [];
    $/* Replaced /* Replaced /* Replaced client */ */ */->sendAll($requests, [
        'error' => function (ErrorEvent $event) use (&$errors) {
            echo 'Request failed: ' . $event->getRequest()->getUrl() . "\n";
            echo $event->getException();
            $errors[] = $event;
        }
    ]);

    foreach ($errors as $error) {
        // ...
    }

Throwing Errors Immediately
~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can throw exceptions immediately as they are encountered.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;

    $/* Replaced /* Replaced /* Replaced client */ */ */->sendAll($requests, [
        'error' => function (ErrorEvent $event) use (&$errors) {
            throw $event->getException();
        }
    ]);

.. _request-options:

Request Options
===============

You can customize requests created by a /* Replaced /* Replaced /* Replaced client */ */ */ using **request options**.
Request options control various aspects of a request including, headers,
query string parameters, timeout settings, the body of a request, and much
more.

All of the following examples use the following /* Replaced /* Replaced /* Replaced client */ */ */:

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client(['base_url' => 'http://httpbin.org']);

headers
-------

Associative array of headers to add to the request. Each key is the name of a
header, and each value is a string or array of strings representing the header
field values.

.. code-block:: php

    // Set various headers on a request
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', [
        'headers' => [
            'User-Agent' => 'testing/1.0',
            'Accept'     => 'application/json',
            'X-Foo'      => ['Bar', 'Baz']
        ]
    ]);

body
----

The ``body`` option is used to control the body of an entity enclosing request
(e.g., PUT, POST, PATCH). This setting can be set to any of the following types:

- string

  .. code-block:: php

      // You can send requests that use a string as the message body.
      $/* Replaced /* Replaced /* Replaced client */ */ */->put('/put', ['body' => 'foo']);

- resource returned from ``fopen()``

  .. code-block:: php

      // You can send requests that use a stream resource as the body.
      $resource = fopen('http://httpbin.org', 'r');
      $/* Replaced /* Replaced /* Replaced client */ */ */->put('/put', ['body' => $resource]);

- Array

  Use an array to send POST style requests that use a
  ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Post\PostBodyInterface`` object as the body.

  .. code-block:: php

      // You can send requests that use a POST body containing fields & files.
      $/* Replaced /* Replaced /* Replaced client */ */ */->post('/post', [
          'body' => [
              'field' => 'abc',
              'other_field' => '123',
              'file_name' => fopen('/path/to/file', 'r')
          ]
      ]);

- ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface``

  .. code-block:: php

      // You can send requests that use a /* Replaced /* Replaced /* Replaced Guzzle */ */ */ stream object as the body
      $stream = /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream::factory('contents...');
      $/* Replaced /* Replaced /* Replaced client */ */ */->post('/post', ['body' => $stream]);

query
-----

Associative array of query string values to add to the request.

.. code-block:: php

    // Send a GET request to /get?foo=bar
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', ['query' => ['foo' => 'bar']);

Query strings specified in the ``query`` option are combined with any query
string values that are parsed from the URL.

.. code-block:: php

    // Send a GET request to /get?abc=123&foo=bar
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get?abc=123', ['query' => ['foo' => 'bar']);

auth
----

Array of HTTP authentication parameters to use with the request. The array must
contain the username in index [0], the password in index [1], and can optionally
contain the authentication type in index [2].

The authentication types are as follows:

Basic
    Use `basic HTTP authentication <http://www.ietf.org/rfc/rfc2069.txt>`_ in
    the ``Authorization`` header (the default setting used if none is
    specified).

    .. code-block:: php

        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', ['auth' => ['username', 'password']]);

Digest
    Use `digest authentication <http://www.ietf.org/rfc/rfc2069.txt>`_ (must be
    supported by the HTTP adapter).

    .. code-block:: php

        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', ['auth' => ['username', 'password', 'Digest']]);

NTLM
    Uses `NTLM authentication <http://msdn.microsoft.com/en-us/library/windows/desktop/aa378749(v=vs.85).aspx>`_.
    (must be supported by the HTTP adapter).

    .. code-block:: php

        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', ['auth' => ['username', 'password', 'NTLM']]);

cookies
-------

Set to ``true`` to use a shared cookie session associated with the /* Replaced /* Replaced /* Replaced client */ */ */.

.. code-block:: php

    // Enable cookies using the shared cookie jar of the /* Replaced /* Replaced /* Replaced client */ */ */.
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', ['cookies' => true]);

Pass an associative array containing cookies to send in the request and start a
new cookie session.

.. code-block:: php

    // Enable cookies and send specific cookies
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', ['cookies' => ['foo' => 'bar']]);

Set to a ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\CookieJar\CookieJarInterface`` object to use an existing
cookie jar.

.. code-block:: php

    $jar = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\CookieJar\ArrayCookieJar();
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', ['cookies' => $jar]);

allow_redirects
---------------

Set to ``false`` to disable redirects.

.. code-block:: php

    $res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/redirect/3', ['allow_redirects' => false]);
    echo $res->getStatusCode();
    // 302

Set to ``true`` (the default setting) to enable normal redirects with a maximum
number of 5 redirects.

.. code-block:: php

    $res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/redirect/3');
    echo $res->getStatusCode();
    // 200

Pass an associative array containing the 'max' key to specify the maximum
number of redirects and optionally provide a 'strict' key value to specify
whether or not to use strict RFC compliant redirects (meaning redirect POST
requests with POST requests vs. doing what most browsers do which is redirect
POST requests with GET requests).

.. code-block:: php

    $res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/redirect/3', [
        'allow_redirects' => [
            'max'    => 10,
            'strict' => true
        ]
    ]);
    echo $res->getStatusCode();
    // 200

save_to
-------

Specify where the body of a response will be saved.

Pass a string to specify the path to a file that will store the contents of the
response body.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/stream/20', ['save_to' => '/path/to/file']);

Pass a resource returned from ``fopen()`` to write the response to a PHP stream.

.. code-block:: php

    $resource = fopen('/path/to/file', 'w');
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/stream/20', ['save_to' => $resource]);

Pass a ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface`` object to stream the response body
to an open /* Replaced /* Replaced /* Replaced Guzzle */ */ */ stream.

.. code-block:: php

    $resource = fopen('/path/to/file', 'w');
    $stream = /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream::factory($resource);
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/stream/20', ['save_to' => $stream]);

events
------

Associative array mapping event names to a callable. or an associative array
containing the 'fn' key that maps to a callable, an optional 'priority' key
used to specify the event priority, and an optional 'once' key used to specify
if the event should remove itself the first time it is triggered.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HeadersEvent;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', [
        'events' => [
            'before' => function (BeforeEvent $e) { echo 'Before'; },
            'headers' => function (HeadersEvent $e) { echo 'Headers'; },
            'complete' => function (CompleteEvent $e) { echo 'Complete'; },
            'error' => function (ErrorEvent $e) { echo 'Error'; },
        ]
    ]);

Here's an example of using the associative array format for control over the
priority and whether or not an event should be triggered more than once.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', [
        'events' => [
            'before' => [
                'fn'       => function (BeforeEvent $e) { echo 'Before'; },
                'priority' => 100,
                'once'     => true
            ]
        ]
    ]);

subscribers
-----------

Array of event subscribers to add to the request. Each value in the array must
be an instance of ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\EventSubscriberInterface``.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\History;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;

    $history = new History();
    $mock = new Mock([new Response(200)]);
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['subscribers' => [$history, $mock]]);

    echo $history;
    // Outputs the request and response history

exceptions
----------

Set to ``false`` to disable throwing exceptions on an HTTP protocol errors
(i.e., 4xx and 5xx responses). Exceptions are thrown by default when HTTP
protocol errors are encountered.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/status/500');
    // Throws a /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ServerException

    $res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/status/500', ['exceptions' => false]);
    echo $res->getStatusCode();
    // 500

timeout
-------

Float describing the timeout of the request in seconds. Use ``0`` to wait
indefinitely (the default behavior).

.. code-block:: php

    // Timeout if a server does not return a response in 3.14 seconds.
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/delay/5', ['timeout' => 3.14]);
    // PHP Fatal error:  Uncaught exception '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException'

connect_timeout
---------------

Float describing the number of seconds to wait while trying to connect to a
server. Use ``0`` to wait indefinitely (the default behavior).

.. code-block:: php

    // Timeout if the /* Replaced /* Replaced /* Replaced client */ */ */ fails to connect to the server in 3.14 seconds.
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/delay/5', ['connect_timeout' => 3.14]);

.. note::

    This setting must be supported by the HTTP adapter used to send a request.
    ``connect_timeout`` is currently only supported by the built-in cURL
    adapter.

verify
------

Set to ``true`` to enable SSL certificate verification (the default). Set to
``false`` to disable certificate verification (this is insecure!). Set to a
string to provide the path to a CA bundle to enable verification using a custom
certificate.

.. code-block:: php

    // Use a custom SSL certificate
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['verify' => '/path/to/cert.pem']);

    // Disable validation
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['verify' => false]);

cert
----

Set to a string to specify the path to a file containing a PEM formatted
/* Replaced /* Replaced /* Replaced client */ */ */ side certificate. If a password is required, then set to an array
containing the path to the PEM file in the first array element followed by the
password required for the certificate in the second array element.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['cert' => ['/path/server.pem', 'password']]);

ssl_key
-------

Specify the path to a file containing a private SSL key in PEM format. If a
password is required, then set to an array containing the path to the SSL key in
the first array element followed by the password required for the certificate
in the second element.

.. note::

    ``ssl_key`` is implemented by HTTP adapters. This is currently only
    supported by the cURL adapter, but might be supported by other third-part
    adapters.

proxy
-----

Pass a string to specify an HTTP proxy.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['proxy' => 'tcp://localhost:8124']);

Pass an associative array to specify HTTP proxies for specific URI schemes
(i.e., "http", "https").

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', [
        'proxy' => [
            'http'  => 'tcp://localhost:8124', // Use this proxy with "http"
            'https' => 'tcp://localhost:9124'  // Use this proxy with "https"
        ]
    ]);

.. note::

    You can provide proxy URLs that contain a scheme, username, and password.
    For example, ``"http://username:password@192.168.16.1:10"``.

debug
-----

Set to ``true`` or set to a PHP stream returned by ``fopen()`` to enable debug
output with the adapter used to send a request. For example, when using cURL to
transfer requests, cURL's verbose of ``CURLOPT_VERBOSE`` will be emitted. When
using the PHP stream wrapper, stream wrapper notifications will be emitted. If
set to true, the output is written to PHP's STDOUT. If a PHP stream is
provided, output is written to the stream.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/get', ['debug' => true]);

Running the above example would output something like the following:

::

    * About to connect() to httpbin.org port 80 (#0)
    *   Trying 107.21.213.98... * Connected to httpbin.org (107.21.213.98) port 80 (#0)
    > GET /get HTTP/1.1
    Host: httpbin.org
    User-Agent: /* Replaced /* Replaced /* Replaced Guzzle */ */ *//4.0 curl/7.21.4 PHP/5.5.7

    < HTTP/1.1 200 OK
    < Access-Control-Allow-Origin: *
    < Content-Type: application/json
    < Date: Sun, 16 Feb 2014 06:50:09 GMT
    < Server: gunicorn/0.17.4
    < Content-Length: 335
    < Connection: keep-alive
    <
    * Connection #0 to host httpbin.org left intact

stream
------

Set to ``true`` to stream a response rather than download it all up-front.

.. code-block:: php

    $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/stream/20', ['stream' => true]);
    // Read bytes off of the stream until the end of the stream is reached
    $body = $response->getBody();
    while (!$body->eof()) {
        echo $body->read(1024);
    }

.. note::

    Streaming response support must be implemented by the HTTP adapter used by
    a /* Replaced /* Replaced /* Replaced client */ */ */. This option might not be supported by every HTTP adapter, but the
    interface of the response object remains the same regardless of whether or
    not it is supported by the adapter.

expect
------

Set to ``true`` to enable the "Expect: 100-Continue" header for all requests
that sends a body. Set to ``false`` to disable the "Expect: 100-Continue"
header for all requests. Set to a number so that the size of the payload must
be greater than the number in order to send the Expect header. Setting to a
number will send the Expect header for all requests in which the size of the
payload cannot be determined or where the body is not rewindable.

By default, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will add the "Expect: 100-Continue" header when the size of
the body of a request is greater than 1 MB.

.. note::

    This option is only supported when using HTTP/1.1 and must be implemented
    by the HTTP adapter used by a /* Replaced /* Replaced /* Replaced client */ */ */.

options
-------

Associative array of options that are forwarded to a request's configuration
collection. These values are used as configuration options that can be consumed
by plugins and adapters.

.. code-block:: php

    $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/get', ['options' => ['foo' => 'bar']]);
    echo $request->getConfig('foo');
    // 'bar'

Some HTTP adapters allow you to specify custom adapter-specific settings. For
example, you can pass custom cURL options to requests by passing an associative
array in the ``options`` request option under the ``curl`` key.

.. code-block:: php

    // Use custom cURL options with the request
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', [
        'options' => [
            'curl' => [
                CURLOPT_FORBID_REUSE  => true,
                CURLOPT_FRESH_CONNECT => true,
            ]
        ]
    ]);

Event Subscribers
=================

Requests emit lifecycle events when they are transferred. A /* Replaced /* Replaced /* Replaced client */ */ */ object has a
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Common\EventEmitter`` object that can be used to add event
*listeners* and event *subscribers* to all requests created by the /* Replaced /* Replaced /* Replaced client */ */ */.

.. important::

    **Every** event listener or subscriber added to a /* Replaced /* Replaced /* Replaced client */ */ */ will be added to
    every request created by the /* Replaced /* Replaced /* Replaced client */ */ */.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();

    // Add a listener that will echo out requests before they are sent
    $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->on('before', function (BeforeEvent $e) {
        echo 'About to send request: ' . $e->getRequest();
    });

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httpbin.org/get');
    // Outputs the request as a string because of the event

See :doc:`events` for more information on the event system used in /* Replaced /* Replaced /* Replaced Guzzle */ */ */.
