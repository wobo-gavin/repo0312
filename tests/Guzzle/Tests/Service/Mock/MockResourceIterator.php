<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ResourceIterator;

class MockResourceIterator extends ResourceIterator
{
    protected function sendRequest()
    {
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $request->getQuery()->set('count', $this->calculatePageSize());
        $data = json_decode($request->send()->getBody(true), true);

        $this->resourceList = $data['resources'];
        $this->nextToken = $data['next_token'];
        $this->retrievedCount += count($this->data['resources']);
        $this->currentIndex = 0;
    }
}
