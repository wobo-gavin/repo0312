<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\FakeParallelAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\TransactionIterator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\FakeParallelAdapter
 */
class FakeParallelAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testSendsAllTransactions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httbin.org'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('HEAD', 'http://httbin.org'),
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

    public function testThrowsImmediatelyIfInstructed()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://httbin.org');
        $request->getEmitter()->on('error', function (ErrorEvent $e) {
            $e->throwImmediately(true);
        });
        $sent = [];
        $f = new FakeParallelAdapter(
            new MockAdapter(function ($trans) use (&$sent) {
                $sent[] = $trans->getRequest()->getMethod();
                return new Response(404);
            })
        );
        $tIter = new TransactionIterator([$request], $/* Replaced /* Replaced /* Replaced client */ */ */, []);
        try {
            $f->sendAll($tIter, 1);
            $this->fail('Did not throw');
        } catch (RequestException $e) {
            $this->assertSame($request, $e->getRequest());
        }
    }
}
