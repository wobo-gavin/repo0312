<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\RuntimeException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\HttpException;

/**
 * Http request exception
 */
class RequestException extends RuntimeException implements HttpException
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Set the request that caused the exception
     *
     * @param RequestInterface $request Request to set
     *
     * @return RequestException
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get the request that caused the exception
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
