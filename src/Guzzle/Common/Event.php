<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

/**
 * Default event for /* Replaced /* Replaced /* Replaced Guzzle */ */ */ notifications
 */
class Event extends SymfonyEvent implements ToArrayInterface, \ArrayAccess, \IteratorAggregate
{
    use HasData;
    
    /**
     * @param array $context Contextual information
     */
    public function __construct(array $context = array())
    {
        $this->data = $context;
    }
}
