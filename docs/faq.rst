===
FAQ
===

Why should I use /* Replaced /* Replaced /* Replaced Guzzle */ */ */?
========================

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ makes it easy to send HTTP requests and super simple to integrate with
web services. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ manages things like persistent connections, represents
query strings as collections, makes it simple to send streaming POST requests
with fields and files, and abstracts away the underlying HTTP transport layer.
By providing an object oriented interface for HTTP /* Replaced /* Replaced /* Replaced client */ */ */s, requests, responses,
headers, and message bodies, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ makes it so that you no longer need to fool
around with cURL options, stream contexts, or sockets.

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ allows you to send both asynchronous and synchronous requests using the
same interface and no direct dependency on an event loop. This flexibility
allows /* Replaced /* Replaced /* Replaced Guzzle */ */ */ to send an HTTP request using the most appropriate HTTP adapter
based on the request being sent. For example, when sending synchronous
requests, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will by default send requests using cURL easy handles to
ensure you're using the fastest possible method for serially transferring HTTP
requests. When sending asynchronous requests, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ might use cURL's multi
interface or any other asynchronous adapter you configure. When you request
streaming data, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will by default use PHP's stream wrapper.

Request and response message bodies use :doc:`/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Streams <streams>`,
allowing you to stream data without needing to load it all into memory.
/* Replaced /* Replaced /* Replaced Guzzle */ */ */'s stream layer provides a large suite of functionality:

- You can modify streams at runtime using custom or a number of
  pre-made decorators.
- You can emit progress events as data is read from a stream.
- You can validate the integrity of a stream using a rolling hash as data is
  read from a stream.

/* Replaced /* Replaced /* Replaced Guzzle */ */ */'s  event system allows you to completely modify the behavior of a /* Replaced /* Replaced /* Replaced client */ */ */
or request at runtime to cater them for any API. You can send a request with a
/* Replaced /* Replaced /* Replaced client */ */ */, and the /* Replaced /* Replaced /* Replaced client */ */ */ can do things like automatically retry your request if
it fails, automatically redirect, log HTTP messages that are sent over the
wire, emit progress events as data is uploaded and downloaded, sign requests
using OAuth 1.0, verify the integrity of messages before and after they are
sent over the wire, and anything else you might need.

Another important aspect of /* Replaced /* Replaced /* Replaced Guzzle */ */ */ is that it's really
:doc:`easy to test /* Replaced /* Replaced /* Replaced client */ */ */s <testing>`. You can mock HTTP responses and when
testing an adapter implementation, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ provides a mock node.js web server.

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ has a large `ecosystem of plugins <http:///* Replaced /* Replaced /* Replaced guzzle */ */ */.readthedocs.org/en/latest/index.html#http-components>`_,
including `service descriptions <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-services>`_
which allows you to abstract web services using service descriptions. These
service descriptions define how to serialize an HTTP request and how to parse
an HTTP response into a more meaningful model object.

- `/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Command <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *//command>`_: Provides the building
  blocks for service description abstraction.
- `/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Services <https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */-services>`_: Provides an
  implementation of "/* Replaced /* Replaced /* Replaced Guzzle */ */ */ Command" that utlizes /* Replaced /* Replaced /* Replaced Guzzle */ */ */'s service description
  format.

Does /* Replaced /* Replaced /* Replaced Guzzle */ */ */ require cURL?
=========================

No. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ can use any HTTP adapter to send requests. This means that /* Replaced /* Replaced /* Replaced Guzzle */ */ */
can be used with cURL, PHP's stream wrapper, sockets, and non-blocking libraries
like `React <http://reactphp.org/>`_. You just need to configure a
`/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Ring <http:///* Replaced /* Replaced /* Replaced guzzle */ */ */-ring.readthedocs.org/en/latest/>`_ adapter to use a
different method of sending requests.

.. note::

    /* Replaced /* Replaced /* Replaced Guzzle */ */ */ has historically only utilized cURL to send HTTP requests. cURL is
    an amazing HTTP /* Replaced /* Replaced /* Replaced client */ */ */ (arguably the best), and /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will continue to use
    it by default when it is available. It is rare, but some developers don't
    have cURL installed on their systems or run into version specific issues.
    By allowing swappable HTTP adapters, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ is now much more customizable
    and able to adapt to fit the needs of more developers.

Can /* Replaced /* Replaced /* Replaced Guzzle */ */ */ send asynchronous requests?
======================================

Yes. Pass the ``future`` true request option to a request to send it
asynchronously. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ will then return a ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\FutureResponse``
object that can be used synchronously by accessing the response object like a
normal response, and it can be used asynchronoulsy using a promise that is
notified when the response is resolved with a real response or rejected with an
exception.

.. code-block:: php

    $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', ['future' => true']);
    $/* Replaced /* Replaced /* Replaced client */ */ */->send($request)->then(function ($response) {
        echo 'Got a response! ' . $response;
    });

You can force an asynchronous response to complete using the ``wait()`` method
of a response.

.. code-block:: php

    $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', ['future' => true']);
    $futureResponse = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
    $futureResponse->wait();

How can I add custom cURL options?
==================================

cURL offer a huge number of `customizable options <http://us1.php.net/curl_setopt>`_.
While /* Replaced /* Replaced /* Replaced Guzzle */ */ */ normalizes many of these options across different adapters, there
are times when you need to set custom cURL options. This can be accomplished
by passing an associative array of cURL settings in the **curl** key of the
**config** request option.

For example, let's say you need to customize the outgoing network interface
used with a /* Replaced /* Replaced /* Replaced client */ */ */.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', [
        'config' => [
            'curl' => [
                CURLOPT_INTERFACE => 'xxx.xxx.xxx.xxx'
            ]
        ]
    ]);

How can I add custom stream context options?
============================================

You can pass custom `stream context options <http://www.php.net/manual/en/context.php>`_
using the **stream_context** key of the **config** request option. The
**stream_context** array is an associative array where each key is a PHP
transport, and each value is an associative array of transport options.

For example, let's say you need to customize the outgoing network interface
used with a /* Replaced /* Replaced /* Replaced client */ */ */ and allow self-signed certificates.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', [
        'stream' => true,
        'config' => [
            'stream_context' => [
                'ssl' => [
                    'allow_self_signed' => true
                ],
                'socket' => [
                    'bindto' => 'xxx.xxx.xxx.xxx'
                ]
            ]
        ]
    ]);

Why am I getting an SSL verification error?
===========================================

You need to specify the path on disk to the CA bundle used by /* Replaced /* Replaced /* Replaced Guzzle */ */ */ for
verifying the peer certificate. See :ref:`verify-option`.

Is it possible to use /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3 and 4 in the same project?
=========================================================

Yes, because /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3 and 4 use different Packagist packages and different
namespaces. You simply need to add ``/* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */`` (/* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3) and
``/* Replaced /* Replaced /* Replaced guzzle */ */ */http//* Replaced /* Replaced /* Replaced guzzle */ */ */`` (/* Replaced /* Replaced /* Replaced Guzzle */ */ */ 4+) to your project's composer.json file.

.. code-block:: javascript

    {
        "require": {
            "/* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */": 3.*,
            "/* Replaced /* Replaced /* Replaced guzzle */ */ */http//* Replaced /* Replaced /* Replaced guzzle */ */ */": 4.*
        }
    }

You might need to use /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3 and /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 4 in the same project due to a
requirement of a legacy application or a dependency that has not yet migrated
to /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 4.0.

What is this Maximum function nesting error?
============================================

    Maximum function nesting level of '100' reached, aborting

You could run into this error if you have the XDebug extension installed and
you execute a lot of requests in callbacks.  This error message comes
specifically from the XDebug extension. PHP itself does not have a function
nesting limit. Change this setting in your php.ini to increase the limit::

    xdebug.max_nesting_level = 1000

[`source <http://stackoverflow.com/a/4293870/151504>`_]

Why am I getting a 417 error response?
======================================

This can occur for a number of reasons, but if you are sending PUT, POST, or
PATCH requests with an ``Expect: 100-Continue`` header, a server that does not
support this header will return a 417 response. You can work around this by
setting the ``expect`` request option to ``false``:

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client();

    // Disable the expect header on a single request
    $response = $/* Replaced /* Replaced /* Replaced client */ */ */->put('/', [], 'the body', [
        'expect' => false
    ]);

    // Disable the expect header on all /* Replaced /* Replaced /* Replaced client */ */ */ requests
    $/* Replaced /* Replaced /* Replaced client */ */ */->setDefaultOption('expect', false)
