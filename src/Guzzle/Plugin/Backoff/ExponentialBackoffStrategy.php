<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\HttpException;

/**
 * Implements an exponential backoff retry strategy. If no strategies are before this in the chain, then all requests
 * will be retried using exponential backoff.
 */
class ExponentialBackoffStrategy extends AbstractBackoffStrategy
{
    /**
     * {@inheritdoc}
     */
    public function makesDecision()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDelay($retries, RequestInterface $request, Response $response = null, HttpException $e = null)
    {
        return (int) pow(2, $retries);
    }
}
