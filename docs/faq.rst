===
FAQ
===

Is it possible to use /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3 and 4 in the same project?
=========================================================

Yes, because /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3 and 4 use different Packagist packages and different
namespaced. You simply need to add ``/* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */`` (/* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3) and
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

How do I migrate from /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 3 to 4?
====================================

See https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ *//blob/master/UPGRADING.md#3x-to-40.

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
    $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig('defaults/expect', false)

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
