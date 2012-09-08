<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\HttpException;

/**
 * Implements a linear backoff retry strategy. If no strategies are before this in the chain, then all requests
 * will be retried using linear backoff.
 */
class LinearBackoffStrategy extends AbstractBackoffStrategy
{
    /**
     * @var int Amount of time to progress each delay
     */
    protected $step;

    /**
     * @param int $step Amount of time to increase the delay each additional backoff
     */
    public function __construct($step = 1)
    {
        $this->step = $step;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDelay($retries, RequestInterface $request, Response $response = null, HttpException $e = null)
    {
        return $retries * $this->step;
    }
}
