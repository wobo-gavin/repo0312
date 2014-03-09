<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter\Curl;

require_once __DIR__ . '/../../Server.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\MultiAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\RequestMediator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HeadersEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\RequestMediator
 */
class RequestMediatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server */
    static $server;

    public static function setUpBeforeClass()
    {
        self::$server = new Server();
        self::$server->start();
    }

    public static function tearDownAfterClass()
    {
        self::$server->stop();
    }

    public function testSetsResponseBodyForDownload()
    {
        $body = Stream::factory();
        $request = new Request('GET', 'http://httbin.org');
        $ee = null;
        $request->getEmitter()->on(
            'headers',
            function (HeadersEvent $e) use (&$ee) {
                $ee = $e;
            }
        );
        $t = new Transaction(new Client(), $request);
        $m = new RequestMediator($t, new MessageFactory());
        $m->setResponseBody($body);
        $this->assertEquals(18, $m->receiveResponseHeader(null, "HTTP/1.1 202 FOO\r\n"));
        $this->assertEquals(10, $m->receiveResponseHeader(null, "Foo: Bar\r\n"));
        $this->assertEquals(11, $m->receiveResponseHeader(null, "Baz : Bam\r\n"));
        $this->assertEquals(19, $m->receiveResponseHeader(null, "Content-Length: 3\r\n"));
        $this->assertEquals(2, $m->receiveResponseHeader(null, "\r\n"));
        $this->assertNotNull($ee);
        $this->assertEquals(202, $t->getResponse()->getStatusCode());
        $this->assertEquals('FOO', $t->getResponse()->getReasonPhrase());
        $this->assertEquals('Bar', $t->getResponse()->getHeader('Foo'));
        $this->assertEquals('Bam', $t->getResponse()->getHeader('Baz'));
        $m->writeResponseBody(null, 'foo');
        $this->assertEquals('foo', (string) $body);
        $this->assertEquals('3', $t->getResponse()->getHeader('Content-Length'));
    }

    public function testSendsToNewBodyWhenNot2xxResponse()
    {
        $body = Stream::factory();
        $request = new Request('GET', 'http://httbin.org');
        $t = new Transaction(new Client(), $request);
        $m = new RequestMediator($t, new MessageFactory());
        $m->setResponseBody($body);
        $this->assertEquals(27, $m->receiveResponseHeader(null, "HTTP/1.1 304 Not Modified\r\n"));
        $this->assertEquals(2, $m->receiveResponseHeader(null, "\r\n"));
        $this->assertEquals(304, $t->getResponse()->getStatusCode());
        $m->writeResponseBody(null, 'foo');
        $this->assertEquals('', (string) $body);
        $this->assertEquals('foo', (string) $t->getResponse()->getBody());
    }

    public function testUsesDefaultBodyIfNoneSet()
    {
        $t = new Transaction(new Client(), new Request('GET', 'http://httbin.org'));
        $t->setResponse(new Response(200));
        $m = new RequestMediator($t, new MessageFactory());
        $this->assertEquals(3, $m->writeResponseBody(null, 'foo'));
        $this->assertEquals('foo', (string) $t->getResponse()->getBody());
    }

    public function testCanUseResponseBody()
    {
        $body = Stream::factory();
        $t = new Transaction(new Client(), new Request('GET', 'http://httbin.org'));
        $t->setResponse(new Response(200, [], $body));
        $m = new RequestMediator($t, new MessageFactory());
        $this->assertEquals(3, $m->writeResponseBody(null, 'foo'));
        $this->assertEquals('foo', (string) $body);
    }

    public function testHandlesTransactionWithNoResponseWhenWritingBody()
    {
        $t = new Transaction(new Client(), new Request('GET', 'http://httbin.org'));
        $m = new RequestMediator($t, new MessageFactory());
        $this->assertEquals(0, $m->writeResponseBody(null, 'test'));
    }

    public function testReadsFromRequestBody()
    {
        $body = Stream::factory('foo');
        $t = new Transaction(new Client(), new Request('PUT', 'http://httbin.org', [], $body));
        $m = new RequestMediator($t, new MessageFactory());
        $this->assertEquals('foo', $m->readRequestBody(null, null, 3));
    }

    public function testEmitsHeadersEventForHeadRequest()
    {
        self::$server->enqueue(["HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nOK"]);
        $ee = null;
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => new MultiAdapter(new MessageFactory())]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->head(self::$server->getUrl(), [
            'events' => [
                'headers' => function (HeadersEvent $e) use (&$ee) {
                    $ee = $e;
                }
            ]
        ]);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\\Event\\HeadersEvent', $ee);
    }
}
