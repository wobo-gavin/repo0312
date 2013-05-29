<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * Default strategy used to determine of an HTTP request can be cached
 */
class DefaultCanCacheStrategy implements CanCacheStrategyInterface
{
    public function canCacheRequest(RequestInterface $request)
    {
        return $request->canCache();
    }

    public function canCacheResponse(Response $response)
    {
        return $response->isSuccessful() && $response->canCache();
    }
}
