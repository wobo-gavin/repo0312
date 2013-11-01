============
Async plugin
============

The AsyncPlugin allows you to send requests that do not wait on a response. This is handled through cURL by utilizing
the progress event. When a request has sent all of its data to the remote server, /* Replaced /* Replaced /* Replaced Guzzle */ */ */ adds a 1ms timeout on the
request and instructs cURL to not download the body of the response. The async plugin then catches the exception and
adds a mock response to the request, along with an X-/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Async header to let you know that the response was not
fully downloaded.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Async\AsyncPlugin;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.example.com');
    $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new AsyncPlugin());
    $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send();
