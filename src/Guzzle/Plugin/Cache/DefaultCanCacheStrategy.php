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
        // Only GET and HEAD requests can be cached
        if ($request->getMethod() != RequestInterface::GET && $request->getMethod() != RequestInterface::HEAD) {
            return false;
        }

        // Never cache requests when using no-store
        if ($request->hasHeader('Cache-Control') && $request->getHeader('Cache-Control')->hasDirective('no-store')) {
            return false;
        }

        return true;
    }

    public function canCacheResponse(Response $response)
    {
        return $response->isSuccessful() && $response->canCache();
    }
}
