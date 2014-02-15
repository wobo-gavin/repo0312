.. title:: /* Replaced /* Replaced /* Replaced Guzzle */ */ */ | PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ and framework for consuming RESTful web services

======
/* Replaced /* Replaced /* Replaced Guzzle */ */ */
======

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ is a PHP HTTP /* Replaced /* Replaced /* Replaced client */ */ */ that is easy to customize.

- Pluggable HTTP adapters for sending requests serially or in parallel
- Does not require cURL, but ships with a built-in cURL adapter that provides
  parallel requests and persistent connections.
- Streams request and response bodies.
- Event driven customization hooks.
- Small core library.
- Plugins for caching, logging, OAuth, mocks, and more.

.. code-block:: php

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client();
    $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http:///* Replaced /* Replaced /* Replaced guzzle */ */ */php.org');
    $res = $/* Replaced /* Replaced /* Replaced client */ */ */->get('https://api.github.com/user', ['auth' =>  ['user', 'pass']]);
    echo $res->statusCode();
    // 200
    echo $res->getHeader('content-type');
    // 'application/json; charset=utf8'
    echo $res->getBody();
    // {"type":"User"...'
    var_export($res->json());
    // Outputs the JSON decoded data

User guide
----------

.. toctree::
    :maxdepth: 2

    overview
    quickstart
    /* Replaced /* Replaced /* Replaced client */ */ */s
    requests
    events
    streams
    faq

Libraries
---------

There are a number of libraries that can be used on top of or alongside
/* Replaced /* Replaced /* Replaced Guzzle */ */ */. Here is a list of components that makeup /* Replaced /* Replaced /* Replaced Guzzle */ */ */ itself, official
libraries provided by the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ organization, and commonly used libraries
provided by third party developers.

.. toctree::
    :maxdepth: 2

    libraries/components
    libraries//* Replaced /* Replaced /* Replaced guzzle */ */ */
    libraries//* Replaced /* Replaced /* Replaced guzzle */ */ */-service
    libraries/third-party

API Documentation
-----------------

.. toctree::
    :maxdepth: 2

    api
