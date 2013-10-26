<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\BatchAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\FutureResponseInterface;

class Pool implements IterablePoolInterface
{
    /** @var ClientInterface Client used to send requests */
    private $/* Replaced /* Replaced /* Replaced client */ */ */;

    /** @var int Number of requests to send in parallel */
    private $concurrency;

    /**
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */      Client used to send requests
     * @param int             $concurrency Number of requests to send in parallel
     */
    public function __construct(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */, $concurrency = 25)
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->concurrency = max(1, $concurrency);
    }

    public function send($requests)
    {
        $queue = [];
        /* @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface $request */
        foreach ($requests as $request) {
            $request->getConfig()['future'] = true;
            $response = $this->/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
            // Not all /* Replaced /* Replaced /* Replaced client */ */ */s have future or batch support
            if (!($response instanceof FutureResponseInterface) ||
                !($response->getAdapter() instanceOf BatchAdapterInterface)
            ) {
                yield $request => $response;
                continue;
            }
            $queue[] = $response;
            if (count($queue) > $this->concurrency) {
                foreach ($this->sendQueue($queue) as $request => $response) {
                    yield $request => $response;
                }
                $queue = [];
            }
        }

        if ($queue) {
            foreach ($this->sendQueue($queue) as $request => $response) {
                yield $request => $response;
            }
        }
    }

    private function sendQueue(array $responses)
    {
        $adapters = new \SplObjectStorage();
        /* @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\FutureResponseInterface $future */
        foreach ($responses as $future) {
            $adapter = $future->getAdapter();
            if (!isset($adapters[$adapter])) {
                $adapters[$adapter] = [$future->getTransaction()];
            } else {
                $list = $adapters[$adapter];
                $list[] = $future->getTransaction();
                $adapters[$adapter] = $list;
            }
        }

        foreach ($adapters as $adapter) {
            $adapter->batch($adapters[$adapter]);
        }

        foreach ($responses as $future) {
            $transaction = $future->getTransaction();
            yield $transaction->getRequest() => $transaction->getResponse();
        }
    }
}
