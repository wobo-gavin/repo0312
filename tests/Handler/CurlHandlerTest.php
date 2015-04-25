<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Test\Handler;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ConnectException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\CurlHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\CurlHandler
 */
class CurlHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if (!function_exists('curl_reset')) {
            $this->markTestSkipped('curl_reset() is not available');
        }
    }

    protected function getHandler($options = [])
    {
        return new CurlHandler($options);
    }

    public function testCanSetMaxHandles()
    {
        $a = new CurlHandler(['max_handles' => 10]);
        $this->assertEquals(10, $this->readAttribute($a, 'maxHandles'));
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

    public function testReleasesAdditionalEasyHandles()
    {
        Server::flush();
        $response = new Response(200, ['Content-Length' => 4], 'test');
        Server::enqueue([$response, $response, $response, $response]);
        $a = new CurlHandler(['max_handles' => 2]);
        $fn = function () use (&$calls, $a, &$fn) {
            if (++$calls < 4) {
                $request = new Request('GET', Server::$url);
                $a($request, ['progress' => $fn]);
            }
        };
        $request = new Request('GET', Server::$url);
        $a($request, ['progress' => $fn]);
        $this->assertCount(2, $this->readAttribute($a, 'handles'));
    }

    public function testReusesHandles()
    {
        Server::flush();
        $response = new response(200);
        Server::enqueue([$response, $response]);
        $a = new CurlHandler();
        $request = new Request('GET', Server::$url);
        $a($request, []);
        $a($request, []);
    }

    public function testDoesSleep()
    {
        $response = new response(200);
        Server::enqueue([$response]);
        $a = new CurlHandler();
        $request = new Request('GET', Server::$url);
        $s = microtime(true);
        $a($request, ['delay' => 0.1])->wait();
        $this->assertGreaterThan(0.0001, microtime(true) - $s);
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
}
