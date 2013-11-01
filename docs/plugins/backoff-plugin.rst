====================
Backoff retry plugin
====================

The ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\BackoffPlugin`` automatically retries failed HTTP requests using custom backoff strategies:

.. code-block:: php

    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
    use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\BackoffPlugin;

    $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
    // Use a static factory method to get a backoff plugin using the exponential backoff strategy
    $backoffPlugin = BackoffPlugin::getExponentialBackoff();

    // Add the backoff plugin to the /* Replaced /* Replaced /* Replaced client */ */ */ object
    $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($backoffPlugin);

The BackoffPlugin's constructor accepts a ``/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\BackoffStrategyInterface`` object that is used to
determine when a retry should be issued and how long to delay between retries. The above code example shows how to
attach a BackoffPlugin to a /* Replaced /* Replaced /* Replaced client */ */ */ that is pre-configured to retry failed 500 and 503 responses using truncated
exponential backoff (emulating the behavior of /* Replaced /* Replaced /* Replaced Guzzle */ */ */ 2's ExponentialBackoffPlugin).
