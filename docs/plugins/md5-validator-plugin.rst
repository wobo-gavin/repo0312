====================
MD5 validator plugin
====================

Entity bodies can sometimes be modified over the wire due to a faulty TCP transport or misbehaving proxy. If an HTTP
response contains a Content-MD5 header, then a MD5 hash of the entity body of a response can be compared against the
Content-MD5 header of the response to determine if the response was delivered intact. The
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Md5\Md5ValidatorPlugin`` will throw an ``UnexpectedValueException`` if the calculated MD5 hash does
not match the Content-MD5 header value:

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Md5\Md5ValidatorPlugin;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');

    $md5Plugin = new Md5ValidatorPlugin();

    // Add the md5 plugin to the /* Replaced /* Replaced /* Replaced client */ */ */ object
    $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($md5Plugin);

    $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.yahoo.com/');
    $request->send();

Calculating the MD5 hash of a large entity body or an entity body that was transferred using a Content-Encoding is an
expensive operation. When working in high performance applications, you might consider skipping the MD5 hash
validation for entity bodies bigger than a certain size or Content-Encoded entity bodies
(see ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Md5\Md5ValidatorPlugin`` for more information).
