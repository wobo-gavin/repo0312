<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;

class BeforeSendEvent extends Event
{
    public function __construct(RequestInterface $request, MessageFactoryInterface $factory)
    {
        parent::__construct([
            'request'         => $request,
            'message_factory' => $factory
        ]);
    }

    public function getRequest()
    {
        return $this['request'];
    }

    public function getMessageFactory()
    {
        return $this['message_factory'];
    }

    public function getResponse()
    {
        return $this['response'];
    }

    public function setResponse(ResponseInterface $response)
    {
        $this['response'] = $response;
    }
}
