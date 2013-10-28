<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Pool;

require_once __DIR__ . '/../Server.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\StreamAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\FutureResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool\ExceptionHandlerPool;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool\Pool;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool\ExceptionHandlerPool
 */
class ExceptionHandlerPoolTest extends \PHPUnit_Framework_TestCase
{
    /** @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server */
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

    public function testYieldsGoodResponses()
    {
        self::$server->flush();
        self::$server->enqueue([
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 201 OK\r\nContent-Length: 0\r\n\r\n"
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => self::$server->getUrl()]);
        $pool = new ExceptionHandlerPool(new Pool($/* Replaced /* Replaced /* Replaced client */ */ */, 2), function () {});
        $gen = function (ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */) {
            for ($i = 0; $i < 2; $i++) {
                yield $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/' . $i);
            }
        };
        foreach ($pool->send($gen($/* Replaced /* Replaced /* Replaced client */ */ */)) as $request => $response) {
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface', $request);
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface', $response);
        }

        $this->assertCount(2, self::$server->getReceivedRequests());
    }

    public function testEmitsErrorEvents()
    {
        self::$server->flush();
        self::$server->enqueue([
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 201 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 404 Not Found\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 201 OK\r\nContent-Length: 0\r\n\r\n"
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => self::$server->getUrl()]);
        $ev = null;
        $pool = new ExceptionHandlerPool(new Pool($/* Replaced /* Replaced /* Replaced client */ */ */), function (RequestErrorEvent $e) use (&$ev) {
            $ev = $e;
        });

        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/')
        ];

        foreach ($pool->send($requests) as $request => $response) {
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface', $request);
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface', $response);
        }

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestErrorEvent', $ev);
        $this->assertCount(4, self::$server->getReceivedRequests());
    }
}
