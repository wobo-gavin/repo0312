<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\FakeParallelAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\TransactionIterator;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\FakeParallelAdapter
 */
class FakeParallelAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testSendsAllTransactions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('HEAD', '/'),
        ];

        $sent = [];
        $f = new FakeParallelAdapter(new MockAdapter(function ($trans) use (&$sent) {
            $sent[] = $trans->getRequest()->getMethod();
            return new Response(200);
        }));

        $tIter = new TransactionIterator($requests, $/* Replaced /* Replaced /* Replaced client */ */ */, []);
        $f->sendAll($tIter, 2);
        $this->assertContains('GET', $sent);
        $this->assertContains('HEAD', $sent);
    }
}
