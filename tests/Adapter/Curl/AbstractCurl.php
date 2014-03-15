<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter\Curl;

require_once __DIR__ . '/../../Server.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Url;

abstract class AbstractCurl extends \PHPUnit_Framework_TestCase
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

    abstract protected function getAdapter($factory = null, $options = []);

    public function testSendsRequest()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: bar\r\nContent-Length: 0\r\n\r\n");
        $t = new Transaction(new Client(), new Request('GET', self::$server->getUrl()));
        $a = $this->getAdapter();
        $response = $a->send($t);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('bar', $response->getHeader('Foo'));
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException
     */
    public function testCatchesErrorWhenPreparing()
    {
        $r = new Request('GET', self::$server->getUrl());
        $f = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\CurlFactory')
            ->setMethods(['createHandle'])
            ->getMock();
        $f->expects($this->once())
            ->method('createHandle')
            ->will($this->throwException(new RequestException('foo', $r)));

        $t = new Transaction(new Client(), $r);
        $a = $this->getAdapter(null, ['handle_factory' => $f]);
        $a->send($t);
    }

    public function testDispatchesAfterSendEvent()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 201 OK\r\nContent-Length: 0\r\n\r\n");
        $r = new Request('GET', self::$server->getUrl());
        $t = new Transaction(new Client(), $r);
        $a = $this->getAdapter();
        $ev = null;
        $r->getEmitter()->on('complete', function (CompleteEvent $e) use (&$ev) {
            $ev = $e;
            $e->intercept(new Response(200, ['Foo' => 'bar']));
        });
        $response = $a->send($t);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('bar', $response->getHeader('Foo'));
    }

    public function testDispatchesErrorEventAndRecovers()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 201 OK\r\nContent-Length: 0\r\n\r\n");
        $r = new Request('GET', self::$server->getUrl());
        $t = new Transaction(new Client(), $r);
        $a = $this->getAdapter();
        $r->getEmitter()->once('complete', function (CompleteEvent $e) {
            throw new RequestException('Foo', $e->getRequest());
        });
        $r->getEmitter()->on('error', function (ErrorEvent $e) {
            $e->intercept(new Response(200, ['Foo' => 'bar']));
        });
        $response = $a->send($t);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('bar', $response->getHeader('Foo'));
    }

    public function testStripsFragmentFromHost()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\n\r\nContent-Length: 0\r\n\r\n");
        // This will fail if the removal of the #fragment is not performed
        $url = Url::fromString(self::$server->getUrl())->setPath(null)->setFragment('foo');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->get($url);
    }
}
