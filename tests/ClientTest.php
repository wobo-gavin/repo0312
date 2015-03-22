<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testUsesDefaultHandler()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        Server::enqueue([new Response(200, ['Content-Length' => 0])]);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get(Server::$url);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Magic request methods require a URI and optional options array
     */
    public function testValidatesArgsForMagicMethods()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->get();
    }

    public function testCanSendMagicAsyncRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        Server::flush();
        Server::enqueue([new Response(200, ['Content-Length' => 2], 'hi')]);
        $p = $/* Replaced /* Replaced /* Replaced client */ */ */->getAsync(Server::$url, ['query' => ['test' => 'foo']]);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface', $p);
        $this->assertEquals(200, $p->wait()->getStatusCode());
        $received = Server::received(true);
        $this->assertCount(1, $received);
        $this->assertEquals('test=foo', $received[0]->getUri()->getQuery());
    }
}
