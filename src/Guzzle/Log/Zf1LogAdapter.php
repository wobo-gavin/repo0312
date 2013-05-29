<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log;

/**
 * Adapts a Zend Framework 1 logger object
 * @deprecated
 */
class Zf1LogAdapter extends AbstractLogAdapter
{
    public function __construct(\Zend_Log $logObject)
    {
        $this->log = $logObject;
    }

    public function log($message, $priority = LOG_INFO, $extras = array())
    {
        $this->log->log($message, $priority, $extras);
    }
}
