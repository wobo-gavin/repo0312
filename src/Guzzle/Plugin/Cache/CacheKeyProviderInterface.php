<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * Used to determine a cache key for a request object
 */
interface CacheKeyProviderInterface
{
    /**
     * Returns a cache key for a request object
     *
     * @param RequestInterface $request Request to generate a cache key for
     *
     * @return string
     */
    public function getCacheKey(RequestInterface $request);
}
