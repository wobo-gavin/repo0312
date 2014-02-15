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

- **base_url**: Configures a base URL for the /* Replaced /* Replaced /* Replaced client */ */ */ so that requests created
  using a relative URL are combined with the ``base_url`` of the /* Replaced /* Replaced /* Replaced client */ */ */
  according to section `5.2 of RFC 3986 <http://tools.ietf.org/html/rfc3986#section-5.2>`_.

  .. code-block:: php

      // Create a /* Replaced /* Replaced /* Replaced client */ */ */ with a base URL
      $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client(['base_url' => 'https://github.com']);
      // Send a request to https://github.com/notifications
      $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/notifications');

  `Absolute URLs <http://tools.ietf.org/html/rfc3986#section-4.3>`_ sent
  through a /* Replaced /* Replaced /* Replaced client */ */ */ will not use the base URL of the /* Replaced /* Replaced /* Replaced client */ */ */.

- **adapter**: Configures the HTTP adapter
  (``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\AdapterInterface``) used to transfer the HTTP requests
  of a /* Replaced /* Replaced /* Replaced client */ */ */. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will, by default, utilize a stacked adapter that chooses
  the best adapter to use based on the provided request options and based on
  the extensions available in the environment. If cURL is installed, it will be
  used as the default adapter. However, if a request has the ``stream`` request
  option, the PHP stream wrapper adapter will be used (assuming
  ``allow_url_fopen`` is enabled in your PHP environment).

- **parallel_adapter**: Just like the ``adapter`` option, you can choose to
  specify an adapter that is used to send requests in parallel
  (``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\ParallelAdapterInterface``). /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will by default
  use cURL to send requests in parallel, but if cURL is not available it will
  use the PHP stream wrapper and simply send requests serially.

- **message_factory**: Specifies the factory used to create HTTP requests and
  responses (``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface``).

- **defaults**: Specified an associative array of request options that are
  applied to every request created by the /* Replaced /* Replaced /* Replaced client */ */ */. This allows you to, for
  example, specifies an array of headers that are sent with every request.

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
        echo $e->getResponse() . "\n";
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

headers
-------

Associative array of headers to add to the request.

body
----

string/resource/array/StreamInterface that represents the body to send over the
wire.

query
-----

Associative array of query string values to add to the request.

auth
----

Array of HTTP authentication parameters to use with the request. The array must
contain the username in index [0], the password in index [2], and can optionally
contain the authentication type in index [3]. The authentication types are:
"Basic", "Digest", "NTLM", "Any" (defaults to "Basic"). The selected
authentication type must be supported by the adapter used by a /* Replaced /* Replaced /* Replaced client */ */ */.

cookies
-------

Pass an associative array containing cookies to send in the request and start a
new cookie session, set to a ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\CookieJar\CookieJarInterface``
object to us an existing cookie jar, or set to ``true`` to use a shared cookie
session associated with the /* Replaced /* Replaced /* Replaced client */ */ */.

allow_redirects
---------------

Set to false to disable redirects. Set to true to enable normal redirects with
a maximum number of 5 redirects. Pass an associative array containing the 'max'
key to specify the maximum number of redirects and optionally provide a 'strict'
key value to specify whether or not to use strict RFC compliant redirects
(meaning redirect POST requests with POST requests vs. doing what most browsers
do which is redirect POST requests with GET requests).

save_to
-------

Specify where the body of a response will be saved. Pass a string to specify
the path to a file that will store the contents of the response body. Pass a
resource returned from fopen to write the response to a PHP stream. Pass a
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface`` object to stream the response body to an open
/* Replaced /* Replaced /* Replaced Guzzle */ */ */ stream.

events
------

Associative array mapping event names to a callable or an associative array
containing the 'fn' key that maps to a callable, an optional 'priority' key
used to specify the event priority, and an optional 'once' key used to specify
if the event should remove itself the first time it is triggered.

subscribers
-----------

Array of event subscribers to add to the request. Each value in the array must
be an instance of ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\EventSubscriberInterface``.

exceptions
----------

Set to false to disable throwing exceptions on an HTTP protocol error (e.g.
404, 500, etc). Exceptions are thrown by default when HTTP protocol errors are
encountered.

timeout
-------

Float describing the timeout of the request in seconds. Use 0 to wait
indefinitely.

connect_timeout
---------------

Float describing the number of seconds to wait while trying to connect. Use 0 to wait
indefinitely. This setting must be supported by the adapter used to send a request.

verify
------

Set to true to enable SSL cert validation (the default), false to disable
validation, or supply the path to a CA bundle to enable verification using a
custom certificate.

cert
----

Set to a string to specify the path to a file containing a PEM formatted
certificate. If a password is required, then set an array containing the path
to the PEM file followed by the the password required for the certificate.

ssl_key
-------

Specify the path to a file containing a private SSL key in PEM format. If a
password is required, then set an array containing the path to the SSL key
followed by the password required for the certificate.

proxy
-----

Specify an HTTP proxy (e.g. ``"http://username:password@192.168.16.1:10"``)

debug
-----

Set to true or a PHP fopen stream resource to enable debug output with the
adapter used to send a request. For example, when using cURL to transfer
requests, cURL's verbose output will be emitted. When using the PHP stream
wrapper, stream wrapper notifications will be emitted. If set to true, the
output is written to PHP's STDOUT.

stream
------

Set to true to stream a response rather than download it all up-front. (Note:
This option might not be supported by every HTTP adapter, but the interface of
the response object remains the same.)

expect
------

Set to true to enable the "Expect: 100-Continue" header for a request that send
a body. Set to false to disable "Expect: 100-Continue". Set to a number so that
the size of the payload must be greater than the number in order to send the
Expect header. Setting to a number will send the Expect header for all requests
in which the size of the payload cannot be determined or where the body is not
rewindable.

options
-------

Associative array of options that are forwarded to a request's configuration
collection. These values are used as configuration options that can be consumed
by plugins and adapters.

Event Subscribers
=================

Requests emit lifecycle events when they are transferred. A /* Replaced /* Replaced /* Replaced client */ */ */ object has a
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Common\EventEmitter`` object that can be used to add event
*listeners* and event *subscribers* to all requests created by the /* Replaced /* Replaced /* Replaced client */ */ */.

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
