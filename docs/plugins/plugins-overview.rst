======================
Plugin system overview
======================

The workflow of sending a request and parsing a response is driven by /* Replaced /* Replaced /* Replaced Guzzle */ */ */'s event system, which is powered by the
`Symfony2 Event Dispatcher component <http://symfony.com/doc/current/components/event_dispatcher/introduction.html>`_.

Any object in /* Replaced /* Replaced /* Replaced Guzzle */ */ */ that emits events will implement the ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasEventDispatcher`` interface. You can add
event subscribers directly to these objects using the ``addSubscriber()`` method, or you can grab the
``Symfony\Component\EventDispatcher\EventDispatcher`` object owned by the object using ``getEventDispatcher()`` and
add a listener or event subscriber.

Adding event subscribers to /* Replaced /* Replaced /* Replaced client */ */ */s
-----------------------------------

Any event subscriber or event listener attached to the EventDispatcher of a ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client`` or
``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client`` object will automatically be attached to all request objects created by the /* Replaced /* Replaced /* Replaced client */ */ */. This
allows you to attach, for example, a HistoryPlugin to a /* Replaced /* Replaced /* Replaced client */ */ */ object, and from that point on, every request sent
through that /* Replaced /* Replaced /* Replaced client */ */ */ will utilize the HistoryPlugin.

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\History\HistoryPlugin;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();

    // Create a history plugin and attach it to the /* Replaced /* Replaced /* Replaced client */ */ */
    $history = new HistoryPlugin();
    $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($history);

    // Create and send a request. This request will also utilize the HistoryPlugin
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httpbin.org')->send();

    // Echo out the last sent request by the /* Replaced /* Replaced /* Replaced client */ */ */
    echo $history->getLastRequest();

.. tip::

    :doc:`Create event subscribers <creating-plugins>`, or *plugins*, to implement reusable logic that can be
    shared across /* Replaced /* Replaced /* Replaced client */ */ */s. Event subscribers are also easier to test than anonymous functions.

Pre-Built plugins
-----------------

/* Replaced /* Replaced /* Replaced Guzzle */ */ */ provides easy to use request plugins that add behavior to requests based on signal slot event notifications
powered by the Symfony2 Event Dispatcher component.

* :doc:`plugins/async-plugin`
* :doc:`plugins/backoff-plugin`
* :doc:`plugins/cache-plugin`
* :doc:`plugins/cookie-plugin`
* :doc:`plugins/curl-auth-plugin`
* :doc:`plugins/history-plugin`
* :doc:`plugins/log-plugin`
* :doc:`plugins/md5-validator-plugin`
* :doc:`plugins/mock-plugin`
* :doc:`plugins/ouath-plugin`

