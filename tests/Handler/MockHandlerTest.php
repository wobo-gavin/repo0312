<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Test\Handler;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\MockHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\MockHandler
 */
class MockHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsMockResponse()
    {
        $res = new Response();
        $mock = new MockHandler([$res]);
        $request = new Request('GET', 'http://example.com');
        $p = $mock($request, []);
        $this->assertSame($res, $p->wait());
    }

    public function testIsCountable()
    {
        $res = new Response();
        $mock = new MockHandler([$res, $res]);
        $this->assertCount(2, $mock);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEnsuresEachAppendIsValid()
    {
        $mock = new MockHandler(['a']);
        $request = new Request('GET', 'http://example.com');
        $mock($request, []);
    }

    public function testCanQueueExceptions()
    {
        $e = new \Exception('a');
        $mock = new MockHandler([$e]);
        $request = new Request('GET', 'http://example.com');
        $p = $mock($request, []);
        $this->assertEquals(PromiseInterface::REJECTED, $p->getState());
        try {
            $p->wait();
            $this->fail();
        } catch (\Exception $e2) {
            $this->assertSame($e, $e2);
        }
    }

    public function testCanGetLastRequestAndOptions()
    {
        $res = new Response();
        $mock = new MockHandler([$res]);
        $request = new Request('GET', 'http://example.com');
        $mock($request, ['foo' => 'bar']);
        $this->assertSame($request, $mock->getLastRequest());
        $this->assertEquals(['foo' => 'bar'], $mock->getLastOptions());
    }

    public function testCanEnqueueCallables()
    {
        $r = new Response();
        $fn = function ($r, $o) use ($r) { return $r; };
        $mock = new MockHandler([$fn]);
        $request = new Request('GET', 'http://example.com');
        $p = $mock($request, ['foo' => 'bar']);
        $this->assertSame($r, $p->wait());
    }

    public function testInvokesOnFulfilled()
    {
        $res = new Response();
        $mock = new MockHandler([$res], function ($v) use (&$c) {
            $c = $v;
        });
        $request = new Request('GET', 'http://example.com');
        $mock($request, [])->wait();
        $this->assertSame($res, $c);
    }

    public function testInvokesOnRejected()
    {
        $e = new \Exception('a');
        $c = null;
        $mock = new MockHandler([$e], null, function ($v) use (&$c) { $c = $v; });
        $request = new Request('GET', 'http://example.com');
        $mock($request, [])->wait(false);
        $this->assertSame($e, $c);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testThrowsWhenNoMoreResponses()
    {
        $mock = new MockHandler();
        $request = new Request('GET', 'http://example.com');
        $mock($request, []);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\BadResponseException
     */
    public function testCanCreateWithDefaultMiddleware()
    {
        $r = new Response(500);
        $mock = MockHandler::createWithMiddleware([$r]);
        $request = new Request('GET', 'http://example.com');
        $mock($request, ['http_errors' => true])->wait();
    }
}
