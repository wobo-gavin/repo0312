<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;

/**
 * PSR-3 log adapter
 *
 * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 */
class PsrLogAdapter extends AbstractLogAdapter
{
    /**
     * syslog to PSR-3 mappings
     */
    private static $mapping = array(
        LOG_DEBUG   => LogLevel::DEBUG,
        LOG_INFO    => LogLevel::INFO,
        LOG_WARNING => LogLevel::WARNING,
        LOG_ERR     => LogLevel::ERROR,
        LOG_CRIT    => LogLevel::CRITICAL,
        LOG_ALERT   => LogLevel::ALERT
    );

    /**
     * {@inheritdoc}
     */
    public function __construct(LoggerInterface $logObject)
    {
        $this->log = $logObject;
    }

    /**
     * {@inheritdoc}
     */
    public function log($message, $priority = LOG_INFO, $extras = null)
    {

        $this->log->log(self::$mapping[$priority], $message, $extras);
    }
}