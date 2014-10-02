<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\EndEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\RingFuture;
use React\Promise\Deferred;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents
 */
class RequestEventsTest extends \PHPUnit_Framework_TestCase
{
    public function prepareEventProvider()
    {
        $cb = function () {};

        return [
            [[], ['complete'], $cb, ['complete' => [$cb]]],
            [
                ['complete' => $cb],
                ['complete'],
                $cb,
                ['complete' => [$cb, $cb]]
            ],
            [
                ['prepare' => []],
                ['error', 'foo'],
                $cb,
                [
                    'prepare' => [],
                    'error'   => [$cb],
                    'foo'     => [$cb]
                ]
            ],
            [
                ['prepare' => []],
                ['prepare'],
                $cb,
                [
                    'prepare' => [$cb]
                ]
            ],
            [
                ['prepare' => ['fn' => $cb]],
                ['prepare'], $cb,
                [
                    'prepare' => [
                        ['fn' => $cb],
                        $cb
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider prepareEventProvider
     */
    public function testConvertsEventArrays(
        array $in,
        array $events,
        $add,
        array $out
    ) {
        $result = RequestEvents::convertEventArray($in, $events, $add);
        $this->assertEquals($out, $result);
    }

    public function adapterResultProvider()
    {
        $deferred = new Deferred();
        $future = new RingFuture(
            $deferred->promise(),
            function () use ($deferred) {
                $deferred->resolve(['status' => 404]);
            }
        );

        return [
            [['status' => 404]],
            [$future]
        ];
    }

    /**
     * @dataProvider adapterResultProvider
     */
    public function testCanInterceptExceptionsInDoneEvent($res)
    {
        $adapter = new MockAdapter($res);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://www.foo.com');
        $request->getEmitter()->on('end', function (EndEvent $e) {
            RequestEvents::stopException($e);
        });
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\CancelledResponse', $response);
        try {
            $response->getStatusCode();
            $this->fail('Did not throw');
        } catch (\Exception $e) {
            $this->assertContains('404', $e->getMessage());
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesEventFormat()
    {
        RequestEvents::convertEventArray(['foo' => false], ['foo'], []);
    }
}
