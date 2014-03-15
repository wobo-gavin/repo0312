<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

require_once __DIR__ . '/Server.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    /** @var \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server */
    public static $server;

    public static function setupBeforeClass()
    {
        self::$server = new Server();
        self::$server->start();
        self::$server->flush();
    }

    public static function tearDownAfterClass()
    {
        self::$server->stop();
    }

    public function testExpandsTemplate()
    {
        $this->assertEquals('foo/123', \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\uriTemplate('foo/{bar}', ['bar' => '123']));
    }

    public function noBodyProvider()
    {
        return [['get'], ['head'], ['delete']];
    }

    /**
     * @dataProvider noBodyProvider
     */
    public function testSendsNoBody($method)
    {
        self::$server->flush();
        self::$server->enqueue([new Response(200)]);
        call_user_func("/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\\{$method}", self::$server->getUrl(), [
            'headers' => ['foo' => 'bar'],
            'query' => ['a' => '1']
        ]);
        $sent = self::$server->getReceivedRequests(true)[0];
        $this->assertEquals(strtoupper($method), $sent->getMethod());
        $this->assertEquals('/?a=1', $sent->getResource());
        $this->assertEquals('bar', $sent->getHeader('foo'));
    }

    public function testSendsOptionsRequest()
    {
        self::$server->flush();
        self::$server->enqueue([new Response(200)]);
        \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\options(self::$server->getUrl(), ['headers' => ['foo' => 'bar']]);
        $sent = self::$server->getReceivedRequests(true)[0];
        $this->assertEquals('OPTIONS', $sent->getMethod());
        $this->assertEquals('/', $sent->getResource());
        $this->assertEquals('bar', $sent->getHeader('foo'));
    }

    public function hasBodyProvider()
    {
        return [['put'], ['post'], ['patch']];
    }

    /**
     * @dataProvider hasBodyProvider
     */
    public function testSendsWithBody($method)
    {
        self::$server->flush();
        self::$server->enqueue([new Response(200)]);
        call_user_func("/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\\{$method}", self::$server->getUrl(), [
            'headers' => ['foo' => 'bar'],
            'body'    => 'test',
            'query'   => ['a' => '1']
        ]);
        $sent = self::$server->getReceivedRequests(true)[0];
        $this->assertEquals(strtoupper($method), $sent->getMethod());
        $this->assertEquals('/?a=1', $sent->getResource());
        $this->assertEquals('bar', $sent->getHeader('foo'));
        $this->assertEquals('test', $sent->getBody());
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     * @expectedExceptionMessage /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\HasDeprecations::baz() is deprecated and will be removed in a future version. Update your code to use the equivalent /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\HasDeprecations::foo() method instead to avoid breaking changes when this shim is removed.
     */
    public function testManagesDeprecatedMethods()
    {
        $d = new HasDeprecations();
        $d->baz();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testManagesDeprecatedMethodsAndHandlesMissingMethods()
    {
        $d = new HasDeprecations();
        $d->doesNotExist();
    }
}

class HasDeprecations
{
    function foo()
    {
        return 'abc';
    }
    function __call($name, $arguments)
    {
        return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\deprecationProxy($this, $name, $arguments, [
            'baz' => 'foo'
        ]);
    }
}
