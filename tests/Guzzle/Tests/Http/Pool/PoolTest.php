<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Pool;

require_once __DIR__ . '/../Server.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\StreamAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\FutureResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool\Pool;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool\Pool
 */
class PoolTest extends \PHPUnit_Framework_TestCase
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

    public function testYieldsImmediatelyForNonFutureResponses()
    {
        $request = new Request('GET', '/');
        $response = new Response(200);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $/* Replaced /* Replaced /* Replaced client */ */ */->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->returnValue($response));
        $pool = new Pool($/* Replaced /* Replaced /* Replaced client */ */ */);
        $results = $pool->send([$request]);
        $this->assertEquals([$response], iterator_to_array($results, false));
    }

    public function testYieldsImmediatelyForNonBatchableAdapters()
    {
        $request = new Request('GET', '/');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $response = new FutureResponse(new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request), new StreamAdapter(new MessageFactory()));
        $/* Replaced /* Replaced /* Replaced client */ */ */->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->returnValue($response));
        $pool = new Pool($/* Replaced /* Replaced /* Replaced client */ */ */);
        $results = $pool->send([$request]);
        $this->assertEquals([$response], iterator_to_array($results, false));
    }

    public function testYieldsResponsesAsTheyComplete()
    {
        self::$server->flush();
        self::$server->enqueue([
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 201 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 202 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 203 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 203 OK\r\nContent-Length: 0\r\n\r\n"
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => self::$server->getUrl()]);
        $pool = new Pool($/* Replaced /* Replaced /* Replaced client */ */ */, 2);
        $gen = function (ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */) {
            for ($i = 0; $i < 4; $i++) {
                yield $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/' . $i);
            }
        };
        foreach ($pool->send($gen($/* Replaced /* Replaced /* Replaced client */ */ */)) as $request => $response) {
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface', $request);
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface', $response);
        }
    }

    public function testThrowsExceptionsImmediately()
    {
        self::$server->flush();
        self::$server->enqueue([
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 404 Not Found\r\nContent-Length: 0\r\n\r\n"
        ]);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => self::$server->getUrl()]);
        $pool = new Pool($/* Replaced /* Replaced /* Replaced client */ */ */, 2);

        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/')
        ];

        try {
            iterator_to_array($pool->send($requests), false);
            $this->fail('Did not throw');
        } catch (RequestException $e) {
            $this->assertCount(2, self::$server->getReceivedRequests());
        }
    }
}
