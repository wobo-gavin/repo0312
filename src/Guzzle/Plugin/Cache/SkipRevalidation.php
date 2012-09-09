<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * Never performs cache revalidation and just assumes the request is still ok
 */
class SkipRevalidation implements RevalidationInterface
{
    /**
     * {@inheritdoc}
     */
    public function revalidate(RequestInterface $request, Response $response)
    {
        return true;
    }
}
