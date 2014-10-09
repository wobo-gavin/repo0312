=============
Ring Adapters
=============

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ uses /* Replaced /* Replaced /* Replaced Guzzle */ */ */-Ring adapters to send HTTP requests over the wire.
/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Ring provides a low-level library that can be used to "glue" /* Replaced /* Replaced /* Replaced Guzzle */ */ */ with
any transport method you choose. By default, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ utilizes cURL and PHP's
stream wrappers to send HTTP requests.

/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Ring adapters makes it extremely simple to integrate /* Replaced /* Replaced /* Replaced Guzzle */ */ */ with any
HTTP transport. For example, you could quite easily bridge /* Replaced /* Replaced /* Replaced Guzzle */ */ */ and React
to use /* Replaced /* Replaced /* Replaced Guzzle */ */ */ in React's event loop.

Using an Adapter
----------------

You can change the adapter used by a /* Replaced /* Replaced /* Replaced client */ */ */ using the `adapter` option in the
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client`` constructor.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\MockAdapter;

    // Create a mock adapter that always returns a 200 response.
    $adapter = new MockAdapter(['status' => 200]);

    // Configure to /* Replaced /* Replaced /* Replaced client */ */ */ to use the mock adapter.
    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter]);

At its core, adapters are simply PHP callables that accept a request array
and return a ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Future\FutureArrayInterface``. This future array
can be used just like a normal PHP array, causing it to block, or you can use
the promise interface using the ``then()`` method of the future. /* Replaced /* Replaced /* Replaced Guzzle */ */ */ hooks
up to the /* Replaced /* Replaced /* Replaced Guzzle */ */ */-Ring project using a very simple bridge class
(``/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RingBridge``).

Creating an Adapter
-------------------

See the `/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Ring <http:///* Replaced /* Replaced /* Replaced guzzle */ */ */-ring.readthedocs.org>`_ project
documentation for more information on creating custom adapters that can be
used with /* Replaced /* Replaced /* Replaced Guzzle */ */ */ /* Replaced /* Replaced /* Replaced client */ */ */s.
