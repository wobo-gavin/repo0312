<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\HttpException;

/**
 * Strategy used to retry HTTP requests when the response's reason phrase matches one of the registered phrases.
 */
class ReasonPhraseBackoffStrategy extends AbstractErrorCodeBackoffStrategy
{
    public function makesDecision()
    {
        return true;
    }

    protected function getDelay($retries, RequestInterface $request, Response $response = null, HttpException $e = null)
    {
        if ($response) {
            return isset($this->errorCodes[$response->getReasonPhrase()]) ? true : null;
        }
    }
}
