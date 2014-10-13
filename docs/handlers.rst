================
RingPHP Handlers
================

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ uses RingPHP handlers to send HTTP requests over the wire.
RingPHP provides a low-level library that can be used to "glue" /* Replaced /* Replaced /* Replaced Guzzle */ */ */ with
any transport method you choose. By default, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ utilizes cURL and PHP's
stream wrappers to send HTTP requests.

RingPHP handlers makes it extremely simple to integrate /* Replaced /* Replaced /* Replaced Guzzle */ */ */ with any
HTTP transport. For example, you could quite easily bridge /* Replaced /* Replaced /* Replaced Guzzle */ */ */ and React
to use /* Replaced /* Replaced /* Replaced Guzzle */ */ */ in React's event loop.

Using a handler
---------------

You can change the handler used by a /* Replaced /* Replaced /* Replaced client */ */ */ using the ``handler`` option in the
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client`` constructor.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\MockHandler;

    // Create a mock handler that always returns a 200 response.
    $handler = new MockHandler(['status' => 200]);

    // Configure to /* Replaced /* Replaced /* Replaced client */ */ */ to use the mock handler.
    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['handler' => $handler]);

At its core, handlers are simply PHP callables that accept a request array
and return a ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Future\FutureArrayInterface``. This future array
can be used just like a normal PHP array, causing it to block, or you can use
the promise interface using the ``then()`` method of the future. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ hooks
up to the RingPHP project using a very simple bridge class
(``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RingBridge``).

Creating a handler
------------------

See the `RingPHP <http:///* Replaced /* Replaced /* Replaced guzzle */ */ */-ring.readthedocs.org>`_ project
documentation for more information on creating custom handlers that can be
used with /* Replaced /* Replaced /* Replaced Guzzle */ */ */ /* Replaced /* Replaced /* Replaced client */ */ */s.
