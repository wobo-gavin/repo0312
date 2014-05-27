<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    public function testExpandsTemplate()
    {
        $this->assertEquals('foo/123', \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\uri_template('foo/{bar}', ['bar' => '123']));
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
        Server::flush();
        Server::enqueue([new Response(200)]);
        call_user_func("/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\\{$method}", Server::$url, [
            'headers' => ['foo' => 'bar'],
            'query' => ['a' => '1']
        ]);
        $sent = Server::received(true)[0];
        $this->assertEquals(strtoupper($method), $sent->getMethod());
        $this->assertEquals('/?a=1', $sent->getResource());
        $this->assertEquals('bar', $sent->getHeader('foo'));
    }

    public function testSendsOptionsRequest()
    {
        Server::flush();
        Server::enqueue([new Response(200)]);
        \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\options(Server::$url, ['headers' => ['foo' => 'bar']]);
        $sent = Server::received(true)[0];
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
        Server::flush();
        Server::enqueue([new Response(200)]);
        call_user_func("/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\\{$method}", Server::$url, [
            'headers' => ['foo' => 'bar'],
            'body'    => 'test',
            'query'   => ['a' => '1']
        ]);
        $sent = Server::received(true)[0];
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

    public function testBatchesRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $responses = [
            new Response(301, ['Location' => 'http://foo.com/bar']),
            new Response(200),
            new Response(200),
            new Response(404)
        ];
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock($responses));
        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com/baz'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('HEAD', 'http://httpbin.org/get'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', 'http://httpbin.org/put'),
        ];

        $a = $b = $c = 0;
        $result = \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\batch($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, [
            'before'   => function (BeforeEvent $e) use (&$a) { $a++; },
            'complete' => function (CompleteEvent $e) use (&$b) { $b++; },
            'error'    => function (ErrorEvent $e) use (&$c) { $c++; },
        ]);

        $this->assertEquals(4, $a);
        $this->assertEquals(2, $b);
        $this->assertEquals(1, $c);
        $this->assertCount(3, $result);

        foreach ($result as $i => $request) {
            $this->assertSame($requests[$i], $request);
        }

        // The first result is actually the second (redirect) response.
        $this->assertSame($responses[1], $result[$requests[0]]);
        // The second result is a 1:1 request:response map
        $this->assertSame($responses[2], $result[$requests[1]]);
        // The third entry is the 404 RequestException
        $this->assertSame($responses[3], $result[$requests[2]]->getResponse());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid event format
     */
    public function testBatchValidatesTheEventFormat()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com/baz')];
        \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\batch($/* Replaced /* Replaced /* Replaced client */ */ */, $requests, ['complete' => 'foo']);
    }

    public function testJsonDecodes()
    {
        $data = \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\json_decode('true');
        $this->assertTrue($data);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to parse JSON data: JSON_ERROR_SYNTAX - Syntax error, malformed JSON
     */
    public function testJsonDecodesWithErrorMessages()
    {
        \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\json_decode('!narf!');
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
        return \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\deprecation_proxy($this, $name, $arguments, [
            'baz' => 'foo'
        ]);
    }
}
