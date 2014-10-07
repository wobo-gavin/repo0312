<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Pool;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Future\FutureArray;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\History;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\EndEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;
use React\Promise\Deferred;

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
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesEachElement()
    {
        $c = new Client();
        $requests = ['foo'];
        $p = new Pool($c, new \ArrayIterator($requests));
        $p->wait();
    }

    public function testSendsAndRealizesFuture()
    {
        $c = $this->getClient();
        $p = new Pool($c, [$c->createRequest('GET', 'http://foo.com')]);
        $this->assertTrue($p->wait());
        $this->assertFalse($p->wait());
        $this->assertTrue($this->readAttribute($p, 'isRealized'));
        $this->assertFalse($p->cancel());
    }

    public function testSendsManyRequestsInCappedPool()
    {
        $c = $this->getClient();
        $p = new Pool($c, [$c->createRequest('GET', 'http://foo.com')]);
        $this->assertTrue($p->wait());
        $this->assertFalse($p->wait());
    }

    public function testSendsRequestsThatHaveNotBeenRealized()
    {
        $c = $this->getClient();
        $p = new Pool($c, [$c->createRequest('GET', 'http://foo.com')]);
        $this->assertTrue($p->wait());
        $this->assertFalse($p->wait());
        $this->assertFalse($p->cancel());
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
        $p->wait();
        $contents = ob_get_clean();
        $this->assertEquals(1, count($h));
        $this->assertEquals('Cancelling', $contents);
    }

    private function getClient()
    {
        $deferred = new Deferred();
        $future = new FutureArray(
            $deferred->promise(),
            function() use ($deferred) {
                $deferred->resolve(['status' => 200, 'headers' => []]);
            }, function () {
                echo 'Cancelling';
            }
        );

        return new Client(['adapter' => new MockAdapter($future)]);
    }

    public function testBatchesRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => function () {
            throw new \RuntimeException('No network access');
        }]);

        $responses = [
            new Response(301, ['Location' => 'http://foo.com/bar']),
            new Response(200),
            new Response(200),
            new Response(404)
        ];

        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock($responses));
        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com/baz'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('HEAD', 'http://httpbin.org/get'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', 'http://httpbin.org/put'),
        ];

        $a = $b = $c = $d = 0;
        $result = Pool::batch($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, [
            'before'   => function (BeforeEvent $e) use (&$a) { $a++; },
            'complete' => function (CompleteEvent $e) use (&$b) { $b++; },
            'error'    => function (ErrorEvent $e) use (&$c) { $c++; },
            'end'      => function (EndEvent $e) use (&$d) { $d++; }
        ]);

        $this->assertEquals(4, $a);
        $this->assertEquals(2, $b);
        $this->assertEquals(1, $c);
        $this->assertEquals(3, $d);
        $this->assertCount(3, $result);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\BatchResults', $result);

        // The first result is actually the second (redirect) response.
        $this->assertSame($responses[1], $result[0]);
        // The second result is a 1:1 request:response map
        $this->assertSame($responses[2], $result[1]);
        // The third entry is the 404 RequestException
        $this->assertSame($responses[3], $result[2]->getResponse());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Each event listener must be a callable or
     */
    public function testBatchValidatesTheEventFormat()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com/baz')];
        Pool::batch($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, ['complete' => 'foo']);
    }

    public function testEmitsProgress()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => function () {
            throw new \RuntimeException('No network access');
        }]);

        $responses = [new Response(200), new Response(404)];
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock($responses));
        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com/baz'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('HEAD', 'http://httpbin.org/get')
        ];

        $pool = new Pool($/* Replaced /* Replaced /* Replaced client */ */ */, $requests);
        $count = 0;
        $thenned = null;
        $pool->then(
            function ($value) use (&$thenned) {
                $thenned = $value;
            },
            null,
            function ($result) use (&$count, $requests) {
                $this->assertSame($requests[$count], $result['request']);
                if ($count == 0) {
                    $this->assertNull($result['error']);
                    $this->assertEquals(200, $result['response']->getStatusCode());
                } else {
                    $this->assertInstanceOf(
                        '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ClientException',
                        $result['error']
                    );
                }
                $count++;
            }
        );

        $pool->wait();
        $this->assertEquals(2, $count);
        $this->assertEquals(true, $thenned);
    }

    public function testDoesNotThrowInErrorEvent()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $responses = [new Response(404)];
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock($responses));
        $requests = [$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com/baz')];
        $result = Pool::batch($/* Replaced /* Replaced /* Replaced client */ */ */, $requests);
        $this->assertCount(1, $result);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ClientException', $result[0]);
    }
}
