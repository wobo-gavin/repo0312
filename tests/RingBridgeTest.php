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
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\RequestEvents;

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
        $fsm = RequestEvents::createFsm();
        $r = RingBridge::prepareRingRequest($trans, $factory, $fsm);
        $this->assertEquals('http', $r['scheme']);
        $this->assertEquals('1.1', $r['version']);
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
        $this->assertFalse($r['future']);
    }

    public function testCreatesRingRequestsWithNullQueryString()
    {
        $request = new Request('GET', 'http://httpbin.org');
        $trans = new Transaction(new Client(), $request);
        $factory = new MessageFactory();
        $fsm = RequestEvents::createFsm();
        $r = RingBridge::prepareRingRequest($trans, $factory, $fsm);
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

    public function testGetsResponseProtocolVersionAndEffectiveUrlAndReason()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'adapter' => new MockAdapter([
                'status'  => 200,
                'reason' => 'test',
                'headers' => [],
                'version' => '1.0',
                'effective_url' => 'http://foo.com'
            ])
        ]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com');
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertEquals('1.0', $response->getProtocolVersion());
        $this->assertEquals('http://foo.com', $response->getEffectiveUrl());
        $this->assertEquals('test', $response->getReasonPhrase());
    }

    public function testGetsStreamFromResponse()
    {
        $res = fopen('php://temp', 'r+');
        fwrite($res, 'foo');
        rewind($res);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'adapter' => new MockAdapter([
                'status'  => 200,
                'headers' => [],
                'body' => $res
            ])
        ]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com');
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertEquals('foo', (string) $response->getBody());
    }

    public function testEmitsCompleteEventOnSuccess()
    {
        $c = false;
        $trans = new Transaction(new Client(), new Request('GET', 'http://f.co'));
        $trans->request->getEmitter()->on('complete', function () use (&$c) {
            $c = true;
        });
        $f = new MessageFactory();
        $res = ['status' => 200, 'headers' => []];
        $fsm = RequestEvents::createFsm();
        RingBridge::completeRingResponse($trans, $res, $f, $fsm);
        $this->assertInstanceOf(
            '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface',
            $trans->response
        );
        $this->assertTrue($c);
    }

    public function testEmitsErrorEventOnError()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://127.0.0.1:123']);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET');
        $called = false;
        $request->getEmitter()->on('error', function () use (&$called) {
            $called = true;
        });
        $request->getConfig()['timeout'] = 0.001;
        $request->getConfig()['connect_timeout'] = 0.001;
        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
            $this->fail('did not throw');
        } catch (RequestException $e) {
            $this->assertSame($request, $e->getRequest());
            $this->assertContains('cURL error', $e->getMessage());
            $this->assertTrue($called);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesRingRequest()
    {
        RingBridge::fromRingRequest([]);
    }

    public function testCreatesRequestFromRing()
    {
        $request = RingBridge::fromRingRequest([
            'http_method' => 'GET',
            'uri' => '/',
            'headers' => [
                'foo' => ['bar'],
                'host' => ['foo.com']
            ],
            'body' => 'test',
            'version' => '1.0'
        ]);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('http://foo.com/', $request->getUrl());
        $this->assertEquals('1.0', $request->getProtocolVersion());
        $this->assertEquals('test', (string) $request->getBody());
        $this->assertEquals('bar', $request->getHeader('foo'));
    }

    public function testCanInterceptException()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://127.0.0.1:123']);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET');
        $called = false;
        $request->getEmitter()->on(
            'error',
            function (ErrorEvent $e) use (&$called) {
                $called = true;
                $e->intercept(new Response(200));
            }
        );
        $request->getConfig()['timeout'] = 0.001;
        $request->getConfig()['connect_timeout'] = 0.001;
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->send($request)->getStatusCode());
        $this->assertTrue($called);
    }
}
