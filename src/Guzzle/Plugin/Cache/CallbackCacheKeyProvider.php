<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * Determines a request's cache key using a callback
 */
class CallbackCacheKeyProvider extends AbstractCallbackStrategy implements CacheKeyProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCacheKey(RequestInterface $request)
    {
        $callback = $this->callback;

        return $callback($request);
    }
}
