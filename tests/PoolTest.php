<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Future;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\History;

class PoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesIterable()
    {
        new Pool(new Client(), 'foo');
    }

    public function testCanControlPoolSizeAndClient()
    {
        $c = new Client();
        $p = new Pool($c, [], ['pool_size' => 10]);
        $this->assertSame($c, $this->readAttribute($p, '/* Replaced /* Replaced /* Replaced client */ */ */'));
        $this->assertEquals(10, $this->readAttribute($p, 'poolSize'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidatesEachElement()
    {
        $c = new Client();
        $requests = ['foo'];
        $p = new Pool($c, new \ArrayIterator($requests));
        $p->deref();
    }

    public function testSendsAndRealizesFuture()
    {
        $c = $this->getClient();
        $p = new Pool($c, [$c->createRequest('GET', 'http://foo.com')]);
        $this->assertTrue($p->deref());
        $this->assertFalse($p->deref());
        $this->assertTrue($p->realized());
        $this->assertFalse($p->cancel());
        $this->assertFalse($p->cancelled());
    }

    public function testHasSendFunction()
    {
        $c = $this->getClient();
        Pool::send($c, [$c->createRequest('GET', 'http://foo.com')]);
    }

    public function testSendsManyRequestsInCappedPool()
    {
        $c = $this->getClient();
        $p = new Pool($c, [$c->createRequest('GET', 'http://foo.com')]);
        $this->assertTrue($p->deref());
        $this->assertFalse($p->deref());
        $this->assertTrue($p->realized());
        $this->assertFalse($p->cancel());
        $this->assertFalse($p->cancelled());
    }

    public function testSendsRequestsThatHaveNotBeenRealized()
    {
        $c = $this->getClient();
        $p = new Pool($c, [$c->createRequest('GET', 'http://foo.com')]);
        $this->assertTrue($p->deref());
        $this->assertFalse($p->deref());
        $this->assertTrue($p->realized());
        $this->assertFalse($p->cancel());
        $this->assertFalse($p->cancelled());
    }

    public function testCancelsInFlightRequests()
    {
        $c = $this->getClient();
        $h = new History();
        $c->getEmitter()->attach($h);
        $p = new Pool($c, [
            $c->createRequest('GET', 'http://foo.com'),
            $c->createRequest('GET', 'http://foo.com', [
                'events' => [
                    'before' => [
                        'fn' => function () use (&$p) {
                            $this->assertTrue($p->cancel());
                        },
                        'priority' => RequestEvents::EARLY
                    ]
                ]
            ])
        ]);
        ob_start();
        $p->deref();
        $contents = ob_get_clean();
        $this->assertTrue($p->cancelled());
        $this->assertEquals(1, count($h));
        $this->assertEquals('Cancelling', $contents);
    }

    private function getClient()
    {
        $future = new Future(function() {
            return ['status' => 200, 'headers' => []];
        }, function () {
            echo 'Cancelling';
        });

        return new Client(['adapter' => new MockAdapter($future)]);
    }
}
