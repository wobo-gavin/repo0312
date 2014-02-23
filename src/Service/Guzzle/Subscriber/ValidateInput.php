<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\SubscriberInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\PrepareEvent;

/**
 * Subscriber used to validate command input against a service description.
 */
class ValidateInput implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['prepare' => ['onPrepare']];
    }

    public function onPrepare(PrepareEvent $event)
    {

    }
}
