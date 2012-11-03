<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * Determines if a request can be cached using a callback
 */
class CallbackCanCacheStrategy extends AbstractCallbackStrategy implements CanCacheStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function canCache(RequestInterface $request)
    {
        return call_user_func($this->callback, $request);
    }
}