<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log;

use Monolog\Logger;

/**
 * Monolog log adapter
 *
 * @link https://github.com/Seldaek/monolog
 */
class MonologLogAdapter extends AbstractLogAdapter
{
    /**
     * syslog to Monolog mappings
     */
    private static $mapping = array(
        LOG_DEBUG   => Logger::DEBUG,
        LOG_INFO    => Logger::INFO,
        LOG_WARNING => Logger::WARNING,
        LOG_ERR     => Logger::ERROR,
        LOG_CRIT    => Logger::CRITICAL,
        LOG_ALERT   => Logger::ALERT
    );

    /**
     * {@inheritdoc}
     */
    public function __construct($logObject)
    {
        if (!($logObject instanceof Logger)) {
            throw new \InvalidArgumentException(
                'Object must be an instance of Monolog\Logger'
            );
        }

        $this->log = $logObject;
    }

    /**
     * {@inheritdoc}
     */
    public function log($message, $priority = LOG_INFO, $extras = null)
    {
        $this->log->addRecord(self::$mapping[$priority], $message);
    }
}