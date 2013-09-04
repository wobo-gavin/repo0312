<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Pool;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * Send FutureRequest objects in parallel
 */
interface PoolInterface
{
    /**
     * Add a request to the pool
     *
     * @param RequestInterface $request Request to add
     */
    public function addRequest(RequestInterface $request);

    /**
     * Send all requests in the pool
     */
    public function send();

    /**
     * Generator that sends each queued request (batchable and non-batchable)
     * and yields RequestInterface objects matching to ResponseInterface
     * objects
     *
     * @return \Generator|void
     */
    public function yieldResponses();
}
