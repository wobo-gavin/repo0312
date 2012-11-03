<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\HttpException;

/**
 * Strategy that will not retry more than a certain number of times.
 */
class TruncatedBackoffStrategy extends AbstractBackoffStrategy
{
    /**
     * @var int Maximum number of retries per request
     */
    protected $max;

    /**
     * @param int                      $maxRetries Maximum number of retries per request
     * @param BackoffStrategyInterface $next The optional next strategy
     */
    public function __construct($maxRetries, BackoffStrategyInterface $next = null)
    {
        $this->max = $maxRetries;
        $this->next = $next;
    }

    /**
     * {@inheritdoc}
     */
    public function makesDecision()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDelay($retries, RequestInterface $request, Response $response = null, HttpException $e = null)
    {
        return $retries < $this->max ? null : false;
    }
}