<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log;

/**
 * Adapter class that allows /* Replaced /* Replaced /* Replaced Guzzle */ */ */ to log data using various logging implementations
 */
abstract class AbstractLogAdapter implements LogAdapterInterface
{
    protected $log;

    public function getLogObject()
    {
        return $this->log;
    }
}
