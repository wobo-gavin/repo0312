<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\HttpException;

/**
 * Strategy used to retry HTTP requests based on the response code.
 * Retries 500 and 503 error by default. If no other strategies are in the chain, it will retry immediately.
 */
class HttpBackoffStrategy extends AbstractErrorCodeBackoffStrategy
{
    /**
     * @var array Default cURL errors to retry
     */
    protected static $defaultErrorCodes = array(500, 503);

    /**
     * {@inheritdoc}
     */
    protected function getDelay($retries, RequestInterface $request, Response $response = null, HttpException $e = null)
    {
        if ($response) {
            //Short circuit the rest of the checks if it was successful
            if ($response->isSuccessful()) {
                return false;
            } else {
                return isset($this->errorCodes[$response->getStatusCode()]) ? true : null;
            }
        }
    }
}
