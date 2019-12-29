<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Handler;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\CurlMultiHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Helpers;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;
use PHPUnit\Framework\TestCase;

class CurlMultiHandlerTest extends TestCase
{
    public function setUp(): void
    {
        $_SERVER['curl_test'] = true;
        unset($_SERVER['_curl_multi']);
    }

    public function tearDown(): void
    {
        unset($_SERVER['_curl_multi'], $_SERVER['curl_test']);
    }

    public function testCanAddCustomCurlOptions()
    {
        Server::flush();
        Server::enqueue([new Response()]);
        $a = new CurlMultiHandler(['options' => [
            CURLMOPT_MAXCONNECTS => 5,
        ]]);
        $request = new Request('GET', Server::$url);
        $a($request, []);
        self::assertEquals(5, $_SERVER['_curl_multi'][CURLMOPT_MAXCONNECTS]);
    }

    public function testSendsRequest()
    {
        Server::enqueue([new Response()]);
        $a = new CurlMultiHandler();
        $request = new Request('GET', Server::$url);
        $response = $a($request, [])->wait();
        self::assertSame(200, $response->getStatusCode());
    }

    public function testCreatesExceptions()
    {
        $a = new CurlMultiHandler();

        $this->expectException(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ConnectException::class);
        $this->expectExceptionMessage('cURL error');
        $a(new Request('GET', 'http://localhost:123'), [])->wait();
    }

    public function testCanSetSelectTimeout()
    {
        $a = new CurlMultiHandler(['select_timeout' => 2]);
        self::assertEquals(2, Helpers::readObjectAttribute($a, 'selectTimeout'));
    }

    public function testCanCancel()
    {
        Server::flush();
        $response = new Response(200);
        Server::enqueue(\array_fill_keys(\range(0, 10), $response));
        $a = new CurlMultiHandler();
        $responses = [];
        for ($i = 0; $i < 10; $i++) {
            $response = $a(new Request('GET', Server::$url), []);
            $response->cancel();
            $responses[] = $response;
        }

        foreach ($responses as $r) {
            self::assertSame('rejected', $response->getState());
        }
    }

    public function testCannotCancelFinished()
    {
        Server::flush();
        Server::enqueue([new Response(200)]);
        $a = new CurlMultiHandler();
        $response = $a(new Request('GET', Server::$url), []);
        $response->wait();
        $response->cancel();
        self::assertSame('fulfilled', $response->getState());
    }

    public function testDelaysConcurrently()
    {
        Server::flush();
        Server::enqueue([new Response()]);
        $a = new CurlMultiHandler();
        $expected = \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\_current_time() + (100 / 1000);
        $response = $a(new Request('GET', Server::$url), ['delay' => 100]);
        $response->wait();
        self::assertGreaterThanOrEqual($expected, \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\_current_time());
    }

    public function testUsesTimeoutEnvironmentVariables()
    {
        $a = new CurlMultiHandler();

        //default if no options are given and no environment variable is set
        self::assertEquals(1, Helpers::readObjectAttribute($a, 'selectTimeout'));

        \putenv("GUZZLE_CURL_SELECT_TIMEOUT=3");
        $a = new CurlMultiHandler();
        $selectTimeout = \getenv('GUZZLE_CURL_SELECT_TIMEOUT');
        //Handler reads from the environment if no options are given
        self::assertEquals($selectTimeout, Helpers::readObjectAttribute($a, 'selectTimeout'));
    }

    public function throwsWhenAccessingInvalidProperty()
    {
        $h = new CurlMultiHandler();

        $this->expectException(\BadMethodCallException::class);
        $h->foo;
    }
}
