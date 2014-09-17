<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ProgressEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RingBridge;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;

class RingBridgeTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesRingRequests()
    {
        $stream = Stream::factory('test');
        $request = new Request('GET', 'http://httpbin.org/get?a=b', [
            'test' => 'hello'
        ], $stream);
        $request->getConfig()->set('foo', 'bar');
        $trans = new Transaction(new Client(), $request);
        $factory = new MessageFactory();
        $r = RingBridge::prepareRingRequest($trans, $factory);
        $this->assertEquals('http', $r['scheme']);
        $this->assertEquals('GET', $r['http_method']);
        $this->assertEquals('http://httpbin.org/get?a=b', $r['url']);
        $this->assertEquals('/get', $r['uri']);
        $this->assertEquals('a=b', $r['query_string']);
        $this->assertEquals([
            'Host' => ['httpbin.org'],
            'test' => ['hello']
        ], $r['headers']);
        $this->assertSame($stream, $r['body']);
        $this->assertEquals(['foo' => 'bar'], $r['/* Replaced /* Replaced /* Replaced client */ */ */']);
        $this->assertTrue(is_callable($r['then']));
    }

    public function testCreatesRingRequestsWithNullQueryString()
    {
        $request = new Request('GET', 'http://httpbin.org');
        $trans = new Transaction(new Client(), $request);
        $factory = new MessageFactory();
        $r = RingBridge::prepareRingRequest($trans, $factory);
        $this->assertNull($r['query_string']);
        $this->assertEquals('/', $r['uri']);
        $this->assertEquals(['Host' => ['httpbin.org']], $r['headers']);
        $this->assertNull($r['body']);
        $this->assertEquals([], $r['/* Replaced /* Replaced /* Replaced client */ */ */']);
    }

    public function testCallsThenAndAddsProgress()
    {
        Server::enqueue([new Response(200)]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => Server::$url]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET');
        $called = false;
        $request->getEmitter()->on(
            'progress',
            function (ProgressEvent $e) use (&$called) {
                $called = true;
            }
        );
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->send($request)->getStatusCode());
        $this->assertTrue($called);
    }

    public function testGetsResponseProtocolVersion()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'adapter' => new MockAdapter([
                'status'  => 200,
                'headers' => [],
                'version' => '1.0'
            ])
        ]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com');
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertEquals('1.0', $response->getProtocolVersion());
    }

    public function testEmitsErrorEventOnError()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://127.0.0.1:123']);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET');
        $request->getConfig()['timeout'] = 0.001;
        $request->getConfig()['connect_timeout'] = 0.001;
        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
            $this->fail('did not throw');
        } catch (RequestException $e) {
            $this->assertSame($request, $e->getRequest());
            $this->assertContains('cURL error', $e->getMessage());
        }
    }
}
