<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;

/**
 * Strategy used to determine if a request can be cached
 */
interface CanCacheStrategyInterface
{
    /**
     * Determine if a request can be cached
     *
     * @param RequestInterface $request Request to determine
     *
     * @return bool
     */
    public function canCacheRequest(RequestInterface $request);

    /**
     * Determine if a response can be cached
     *
     * @param ResponseInterface $response Response to determine
     *
     * @return bool
     */
    public function canCacheResponse(ResponseInterface $response);
}
