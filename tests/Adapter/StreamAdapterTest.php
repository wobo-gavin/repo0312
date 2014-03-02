<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter;

require_once __DIR__ . '/../Server.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\StreamAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\StreamAdapter
 */
class StreamAdapterTest extends \PHPUnit_Framework_TestCase
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

    public function testReturnsResponseForSuccessfulRequest()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 2\r\n\r\nhi");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_url' => self::$server->getUrl(),
            'adapter' => new StreamAdapter(new MessageFactory())
        ]);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['headers' => ['Foo' => 'Bar']]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals('Bar', $response->getHeader('Foo'));
        $this->assertEquals('2', $response->getHeader('Content-Length'));
        $this->assertEquals('hi', $response->getBody());
        $sent = self::$server->getReceivedRequests(true)[0];
        $this->assertEquals('GET', $sent->getMethod());
        $this->assertEquals('/', $sent->getResource());
        $this->assertEquals('127.0.0.1:8124', $sent->getHeader('host'));
        $this->assertEquals('Bar', $sent->getHeader('foo'));
        $this->assertTrue($sent->hasHeader('user-agent'));
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException
     * @expectedExceptionMesssage Invalid URL
     */
    public function testThrowsExceptionsCaughtDuringTransfer()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'adapter' => new StreamAdapter(new MessageFactory()),
            'defaults' => [
                'proxy' => self::$server->getUrl()
            ]
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httbin.org', ['headers' => ['Foo' => 'Bar']]);
    }

    public function testCanHandleExceptionsUsingEvents()
    {
        self::$server->flush();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => new StreamAdapter(new MessageFactory())]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', self::$server->getUrl());
        $mockResponse = new Response(200);
        $request->getEmitter()->on(
            'error',
            function (ErrorEvent $e) use ($mockResponse) {
                $e->intercept($mockResponse);
            }
        );
        $this->assertSame($mockResponse, $/* Replaced /* Replaced /* Replaced client */ */ */->send($request));
    }

    public function testEmitsAfterSendEvent()
    {
        $ee = null;
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 8\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => new StreamAdapter(new MessageFactory())]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', self::$server->getUrl());
        $request->getEmitter()->on('complete', function ($e) use (&$ee) {
            $ee = $e;
        });
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent', $ee);
        $this->assertSame($request, $ee->getRequest());
        $this->assertEquals(200, $ee->getResponse()->getStatusCode());
    }

    public function testStreamAttributeKeepsStreamOpen()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 8\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_url' => self::$server->getUrl(),
            'adapter' => new StreamAdapter(new MessageFactory())
        ]);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->put('/foo', [
            'headers' => ['Foo' => 'Bar'],
            'body' => 'test',
            'stream' => true
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals('8', $response->getHeader('Content-Length'));
        $body = $response->getBody();
        $this->assertEquals('http', $body->getMetadata()['wrapper_type']);
        $this->assertEquals(8, $body->getMetadata()['unread_bytes']);
        $this->assertEquals(self::$server->getUrl() . 'foo', $body->getMetadata()['uri']);
        $this->assertEquals('hi', $body->read(2));
        $body->close();

        $sent = self::$server->getReceivedRequests(true)[0];
        $this->assertEquals('PUT', $sent->getMethod());
        $this->assertEquals('/foo', $sent->getResource());
        $this->assertEquals('127.0.0.1:8124', $sent->getHeader('host'));
        $this->assertEquals('Bar', $sent->getHeader('foo'));
        $this->assertTrue($sent->hasHeader('user-agent'));
    }

    public function testDrainsResponseIntoTempStream()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 8\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_url' => self::$server->getUrl(),
            'adapter' => new StreamAdapter(new MessageFactory())
        ]);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $body = $response->getBody();
        $this->assertEquals('php://temp', $body->getMetadata()['uri']);
        $this->assertEquals('hi', $body->read(2));
        $body->close();
    }

    public function testDrainsResponseIntoSaveToBody()
    {
        $r = fopen('php://temp', 'r+');
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 8\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_url' => self::$server->getUrl(),
            'adapter' => new StreamAdapter(new MessageFactory())
        ]);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['save_to' => $r]);
        $body = $response->getBody();
        $this->assertEquals('php://temp', $body->getMetadata()['uri']);
        $this->assertEquals('hi', $body->read(2));
        $this->assertEquals(' there', stream_get_contents($r));
        $body->close();
    }

    public function testDrainsResponseIntoSaveToBodyAtPath()
    {
        $tmpfname = tempnam('/tmp', 'save_to_path');
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 8\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_url' => self::$server->getUrl(),
            'adapter' => new StreamAdapter(new MessageFactory())
        ]);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['save_to' => $tmpfname]);
        $body = $response->getBody();
        $this->assertEquals($tmpfname, $body->getMetadata()['uri']);
        $this->assertEquals('hi', $body->read(2));
        $body->close();
        unlink($tmpfname);
    }

    public function testAddsGzipFilterIfAcceptHeaderIsPresent()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 8\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_url' => self::$server->getUrl(),
            'adapter' => new StreamAdapter(new MessageFactory())
        ]);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', [
            'headers' => ['Accept-Encoding' => 'gzip'],
            'stream' => true
        ]);
        $body = $response->getBody();
        $this->assertEquals('compress.zlib://http://127.0.0.1:8124/', $body->getMetadata()['uri']);
    }

    protected function getStreamFromBody(Stream $body)
    {
        $r = new \ReflectionProperty($body, 'stream');
        $r->setAccessible(true);

        return $r->getValue($body);
    }

    protected function getSendResult(array $opts)
    {
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 8\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => new StreamAdapter(new MessageFactory())]);

        return $/* Replaced /* Replaced /* Replaced client */ */ */->get(self::$server->getUrl(), $opts);
    }

    public function testAddsProxy()
    {
        $body = $this->getSendResult(['stream' => true, 'proxy' => '127.0.0.1:8124'])->getBody();
        $opts = stream_context_get_options($this->getStreamFromBody($body));
        $this->assertEquals('127.0.0.1:8124', $opts['http']['proxy']);
    }

    public function testAddsTimeout()
    {
        $body = $this->getSendResult(['stream' => true, 'timeout' => 200])->getBody();
        $opts = stream_context_get_options($this->getStreamFromBody($body));
        $this->assertEquals(200, $opts['http']['timeout']);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage SSL certificate authority file not found: /does/not/exist
     */
    public function testVerifiesVerifyIsValidIfPath()
    {
        (new Client([
            'adapter' => new StreamAdapter(new MessageFactory()),
            'base_url' => self::$server->getUrl(),
            'defaults' => ['verify' => '/does/not/exist']
        ]))->get('/');
    }

    public function testVerifyCanBeDisabled()
    {
        self::$server->enqueue("HTTP/1.1 200\r\nContent-Length: 0\r\n\r\n");
        (new Client([
            'adapter' => new StreamAdapter(new MessageFactory()),
            'base_url' => self::$server->getUrl(),
            'defaults' => ['verify' => false]
        ]))->get('/');
    }

    public function testVerifyCanBeSetToPath()
    {
        $path = __DIR__ . '/../../src/cacert.pem';
        $this->assertFileExists($path);
        $body = $this->getSendResult(['stream' => true, 'verify' => $path])->getBody();
        $opts = stream_context_get_options($this->getStreamFromBody($body));
        $this->assertEquals(true, $opts['http']['verify_peer']);
        $this->assertEquals($path, $opts['http']['cafile']);
        $this->assertTrue(file_exists($opts['http']['cafile']));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage SSL certificate not found: /does/not/exist
     */
    public function testVerifiesCertIfValidPath()
    {
        (new Client([
            'adapter' => new StreamAdapter(new MessageFactory()),
            'base_url' => self::$server->getUrl(),
            'defaults' => ['cert' => '/does/not/exist']
        ]))->get('/');
    }

    public function testCanSetPasswordWhenSettingCert()
    {
        $path = __DIR__ . '/../../src/cacert.pem';
        $body = $this->getSendResult(['stream' => true, 'cert' => [$path, 'foo']])->getBody();
        $opts = stream_context_get_options($this->getStreamFromBody($body));
        $this->assertEquals($path, $opts['http']['local_cert']);
        $this->assertEquals('foo', $opts['http']['passphrase']);
    }

    public function testDebugAttributeWritesStreamInfoToTempBufferByDefault()
    {


        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 8\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_url' => self::$server->getUrl(),
            'adapter' => new StreamAdapter(new MessageFactory())
        ]);
        ob_start();
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['debug' => true]);
        $contents = ob_get_clean();
        $this->assertContains('<http://127.0.0.1:8124/>: Connected', $contents);
        $this->assertContains('<http://127.0.0.1:8124/>: Got the filesize: 8', $contents);
        $this->assertContains('<http://127.0.0.1:8124/>: Downloaded 0 bytes', $contents);
    }

    public function testDebugAttributeWritesStreamInfoToBuffer()
    {
        $buffer = fopen('php://temp', 'r+');
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 8\r\nContent-Type: text/plain\r\n\r\nhi there");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_url' => self::$server->getUrl(),
            'adapter' => new StreamAdapter(new MessageFactory())
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['debug' => $buffer]);
        fseek($buffer, 0);
        $contents = stream_get_contents($buffer);
        $this->assertContains('<http://127.0.0.1:8124/>: Connected', $contents);
        $this->assertContains('<http://127.0.0.1:8124/>: Got the filesize: 8', $contents);
        $this->assertContains('<http://127.0.0.1:8124/>: Downloaded 0 bytes', $contents);
        $this->assertContains('<http://127.0.0.1:8124/>: Found the mime-type: text/plain', $contents);
    }
}
