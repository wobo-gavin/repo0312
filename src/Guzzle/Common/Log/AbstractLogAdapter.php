<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log;

/**
 * Adapter class that allows /* Replaced /* Replaced /* Replaced Guzzle */ */ */ to log dato to various logging
 * implementations so that you may use the log classes of your favorite
 * framework.
 */
abstract class AbstractLogAdapter implements LogAdapterInterface
{
    protected $log;

    /**
     * {@inheritdoc}
     */
    public function getLogObject()
    {
        return $this->log;
    }
}