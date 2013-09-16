<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * Event object emitted when a /* Replaced /* Replaced /* Replaced client */ */ */ creates a request.
 */
class ClientCreateRequestEvent extends Event
{
    private $/* Replaced /* Replaced /* Replaced client */ */ */;
    private $request;

    /**
     * @param ClientInterface  $/* Replaced /* Replaced /* Replaced client */ */ */ Client that created the request
     * @param RequestInterface $request Request that was created
     */
    public function __construct(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */, RequestInterface $request)
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->request = $request;
    }

    /**
     * Get the request that was created
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the /* Replaced /* Replaced /* Replaced client */ */ */ that created the request
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}
