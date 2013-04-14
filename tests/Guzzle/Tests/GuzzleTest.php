<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests;

use \/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\History\HistoryPlugin;

/**
 * @group server
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */
 */
class /* Replaced /* Replaced /* Replaced Guzzle */ */ */Test extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function setUp()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
    }

    public function httpMethodProvider()
    {
        return array_map(function ($method) { return array($method); }, array(
            'get', 'head', 'put', 'post', 'delete', 'options', 'patch'
        ));
    }

    /**
     * @dataProvider httpMethodProvider
     */
    public function testSendsHttpRequestsWithMethod($method)
    {
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::$method($this->getServer()->getUrl());
        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals(strtoupper($method), $requests[0]->getMethod());
    }

    public function testCanDisableRedirects()
    {
        $this->getServer()->enqueue(array(
            "HTTP/1.1 307\r\nLocation: " . $this->getServer()->getUrl() . "\r\nContent-Length: 0\r\n\r\n"
        ));
        $response = /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array('allow_redirects' => false));
        $this->assertEquals(307, $response->getStatusCode());
    }

    public function testCanAddCookies()
    {
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array('cookies' => array('Foo' => 'Bar')));
        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('Bar', $requests[0]->getCookie('Foo'));
    }

    public function testCanAddQueryString()
    {
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array('query' => array('Foo' => 'Bar')));
        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('Bar', $requests[0]->getQuery()->get('Foo'));
    }

    public function testCanAddCurl()
    {
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array('curl' => array(CURLOPT_ENCODING => '*')));
        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('*/*', (string) $requests[0]->getHeader('Accept'));
    }

    public function testCanAddAuth()
    {
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array('auth' => array('michael', 'test')));
        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('Basic bWljaGFlbDp0ZXN0', (string) $requests[0]->getHeader('Authorization'));
    }

    public function testCanAddEvents()
    {
        $foo = null;
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array(
            'events' => array(
                'request.complete' => function () use (&$foo) { $foo = true; }
            )
        ));
        $this->assertTrue($foo);
    }

    public function testCanAddPlugins()
    {
        $history = new HistoryPlugin();
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array('plugins' => array($history)));
        $this->assertEquals(1, count($history));
    }

    public function testCanCreateStreams()
    {
        $response = /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array('stream' => true));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface', $response);
    }

    public function testCanCreateStreamsWithCustomFactory()
    {
        $f = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamRequestFactoryInterface')
            ->setMethods(array('fromRequest'))
            ->getMock();
        $f->expects($this->once())
            ->method('fromRequest');
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */::get($this->getServer()->getUrl(), array('stream' => $f));
    }
}
