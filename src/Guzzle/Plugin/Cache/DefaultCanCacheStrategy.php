<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * Default strategy used to determine of an HTTP request can be cached
 */
class DefaultCanCacheStrategy implements CanCacheStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function canCache(RequestInterface $request)
    {
        return $request->canCache();
    }

    /**
     * {@inheritdoc}
     */
    public function canCacheResponse(Response $response)
    {
        return $response->isSuccessful() && $response->canCache();
    }
}
