==============
History plugin
==============

The history plugin tracks all of the requests and responses sent through a request or /* Replaced /* Replaced /* Replaced client */ */ */. This plugin can be
useful for crawling or unit testing. By default, the history plugin stores up to 10 requests and responses.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\History\HistoryPlugin;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');

    // Add the history plugin to the /* Replaced /* Replaced /* Replaced client */ */ */ object
    $history = new HistoryPlugin();
    $history->setLimit(5);
    $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($history);

    $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.yahoo.com/')->send();

    echo $history->getLastRequest();
    echo $history->getLastResponse();
    echo count($history);
