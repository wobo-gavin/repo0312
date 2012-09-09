<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * Cache revalidation interface
 */
interface RevalidationInterface
{
    /**
     * Performs a cache revalidation
     *
     * @param RequestInterface $request    Request to revalidate
     * @param Response         $response   Response that was received
     *
     * @return bool Returns true if the request can be cached
     */
    public function revalidate(RequestInterface $request, Response $response);
}
