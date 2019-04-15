<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Test\Handler;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ConnectException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\CurlHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;
use PHPUnit\Framework\TestCase;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\CurlHandler
 */
class CurlHandlerTest extends TestCase
{
    protected function getHandler($options = [])
    {
        return new CurlHandler($options);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ConnectException
     * @expectedExceptionMessage cURL
     */
    public function testCreatesCurlErrors()
    {
        $handler = new CurlHandler();
        $request = new Request('GET', 'http://localhost:123');
        $handler($request, ['timeout' => 0.001, 'connect_timeout' => 0.001])->wait();
    }

    public function testReusesHandles()
    {
        Server::flush();
        $response = new response(200);
        Server::enqueue([$response, $response]);
        $a = new CurlHandler();
        $request = new Request('GET', Server::$url);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\FulfilledPromise', $a($request, []));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\FulfilledPromise', $a($request, []));
    }

    public function testDoesSleep()
    {
        $response = new response(200);
        Server::enqueue([$response]);
        $a = new CurlHandler();
        $request = new Request('GET', Server::$url);
        $s = \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\_current_time();
        $a($request, ['delay' => 0.1])->wait();
        $this->assertGreaterThan(0.0001, \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\_current_time() - $s);
    }

    public function testCreatesCurlErrorsWithContext()
    {
        $handler = new CurlHandler();
        $request = new Request('GET', 'http://localhost:123');
        $called = false;
        $p = $handler($request, ['timeout' => 0.001, 'connect_timeout' => 0.001])
            ->otherwise(function (ConnectException $e) use (&$called) {
                $called = true;
                $this->assertArrayHasKey('errno', $e->getHandlerContext());
            });
        $p->wait();
        $this->assertTrue($called);
    }

    public function testUsesContentLengthWhenOverInMemorySize()
    {
        Server::flush();
        Server::enqueue([new Response()]);
        $stream = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for(str_repeat('.', 1000000));
        $handler = new CurlHandler();
        $request = new Request(
            'PUT',
            Server::$url,
            ['Content-Length' => 1000000],
            $stream
        );
        $handler($request, [])->wait();
        $received = Server::received()[0];
        $this->assertEquals(1000000, $received->getHeaderLine('Content-Length'));
        $this->assertFalse($received->hasHeader('Transfer-Encoding'));
    }
}
