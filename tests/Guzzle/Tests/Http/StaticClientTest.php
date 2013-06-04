<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Redirect;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\StaticClient;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\StaticClient
 */
class StaticClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testMountsClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        StaticClient::mount('FooBazBar', $/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertTrue(class_exists('FooBazBar'));
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $this->readAttribute('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\StaticClient', '/* Replaced /* Replaced /* Replaced client */ */ */'));
    }

    public function requestProvider()
    {
        return array_map(
            function ($m) { return array($m); },
            array('GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD', 'OPTIONS')
        );
    }

    /**
     * @dataProvider requestProvider
     */
    public function testSendsRequests($method)
    {
        $mock = new MockPlugin(array(new Response(200)));
        call_user_func('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\StaticClient::' . $method, 'http://foo.com', array(
            'plugins' => array($mock)
        ));
        $requests = $mock->getReceivedRequests();
        $this->assertCount(1, $requests);
        $this->assertEquals($method, $requests[0]->getMethod());
    }

    public function testCanCreateStreamsUsingDefaultFactory()
    {
        $this->getServer()->enqueue(array("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ntest"));
        $stream = StaticClient::get($this->getServer()->getUrl(), array('stream' => true));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface', $stream);
        $this->assertEquals('test', (string) $stream);
    }

    public function testCanCreateStreamsUsingCustomFactory()
    {
        $stream = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamRequestFactoryInterface')
            ->setMethods(array('fromRequest'))
            ->getMockForAbstractClass();
        $resource = new Stream(fopen('php://temp', 'r+'));
        $stream->expects($this->once())
            ->method('fromRequest')
            ->will($this->returnValue($resource));
        $this->getServer()->enqueue(array("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ntest"));
        $result = StaticClient::get($this->getServer()->getUrl(), array('stream' => $stream));
        $this->assertSame($resource, $result);
    }
}
