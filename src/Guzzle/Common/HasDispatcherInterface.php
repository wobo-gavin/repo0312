<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Holds an event dispatcher
 */
interface HasDispatcherInterface
{
    /**
     * Get the EventDispatcher of the object
     *
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher();
}
