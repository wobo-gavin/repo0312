<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * Determines if a request can be cached using a callback
 */
class CallbackCanCacheStrategy extends DefaultCanCacheStrategy
{
    /** @var callable Callback for request */
    protected $requestCallback;

    /** @var callable Callback for response */
    protected $responseCallback;

    /**
     * @param callable $requestCallback  Callable method to invoke for requests
     * @param callable $responseCallback Callable method to invoke for responses
     */
    public function __construct(callable $requestCallback = null, callable $responseCallback = null)
    {
        $this->requestCallback = $requestCallback;
        $this->responseCallback = $responseCallback;
    }

    public function canCacheRequest(RequestInterface $request)
    {
        return $this->requestCallback
            ? call_user_func($this->requestCallback, $request)
            : parent::canCacheRequest($request);
    }

    public function canCacheResponse(Response $response)
    {
        return $this->responseCallback
            ? call_user_func($this->responseCallback, $response)
            : parent::canCacheResponse($response);
    }
}
