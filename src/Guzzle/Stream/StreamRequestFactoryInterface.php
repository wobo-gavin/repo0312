<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * Interface used for creating streams from requests
 */
interface StreamRequestFactoryInterface
{
    /**
     * Create a stream based on a request object
     *
     * @param RequestInterface $request Base the stream on a request
     * @param array            $options Custom context options to merge into the default context options
     * @param array            $params  Custom context params to use with the request
     *
     * @return resource Returns a stream resource that can be used like fopen resources
     * @throws \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\RuntimeException if the stream cannot be opened or an error occurs
     */
    public function fromRequest(RequestInterface $request, array $options = null, array $params = array());
}
