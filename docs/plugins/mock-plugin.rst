===========
Mock plugin
===========

The mock plugin is useful for testing /* Replaced /* Replaced /* Replaced Guzzle */ */ */ /* Replaced /* Replaced /* Replaced client */ */ */s. The mock plugin allows you to queue an array of responses that
will satisfy requests sent from a /* Replaced /* Replaced /* Replaced client */ */ */ by consuming the request queue in FIFO order.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');

    $mock = new MockPlugin();
    $mock->addResponse(new Response(200))
         ->addResponse(new Response(404));

    // Add the mock plugin to the /* Replaced /* Replaced /* Replaced client */ */ */ object
    $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($mock);

    // The following request will receive a 200 response from the plugin
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.example.com/')->send();

    // The following request will receive a 404 response from the plugin
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.test.com/')->send();
