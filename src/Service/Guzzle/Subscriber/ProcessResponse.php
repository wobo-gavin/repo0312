<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\SubscriberInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\ProcessEvent;

/**
 * Subscriber used to create response models based on an HTTP response and
 * a service description.
 */
class ProcessResponse implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['process' => ['onProcess']];
    }

    public function onProcess(ProcessEvent $event)
    {

    }
}
