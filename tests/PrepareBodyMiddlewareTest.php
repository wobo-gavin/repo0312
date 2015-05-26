<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\MockHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\HandlerStack;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Middleware;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\FnStream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;
use Psr\Http\Message\RequestInterface;

class PrepareBodyMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testAddsContentLengthWhenMissingAndPossible()
    {
        $h = new MockHandler([
            function (RequestInterface $request) {
                $this->assertEquals(3, $request->getHeaderLine('Content-Length'));
                return new Response(200);
            }
        ]);
        $m = Middleware::prepareBody();
        $stack = new HandlerStack($h);
        $stack->push($m);
        $comp = $stack->resolve();
        $p = $comp(new Request('PUT', 'http://www.google.com', [], '123'), []);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\FulfilledPromise', $p);
        $response = $p->wait();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddsTransferEncodingWhenNoContentLength()
    {
        $body = FnStream::decorate(/* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for('foo'), [
            'getSize' => function () { return null; }
        ]);
        $h = new MockHandler([
            function (RequestInterface $request) {
                $this->assertFalse($request->hasHeader('Content-Length'));
                $this->assertEquals('chunked', $request->getHeaderLine('Transfer-Encoding'));
                return new Response(200);
            }
        ]);
        $m = Middleware::prepareBody();
        $stack = new HandlerStack($h);
        $stack->push($m);
        $comp = $stack->resolve();
        $p = $comp(new Request('PUT', 'http://www.google.com', [], $body), []);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\FulfilledPromise', $p);
        $response = $p->wait();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddsContentTypeWhenMissingAndPossible()
    {
        $bd = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for(fopen(__DIR__ . '/../composer.json', 'r'));
        $h = new MockHandler([
            function (RequestInterface $request) {
                $this->assertEquals('application/json', $request->getHeaderLine('Content-Type'));
                $this->assertTrue($request->hasHeader('Content-Length'));
                return new Response(200);
            }
        ]);
        $m = Middleware::prepareBody();
        $stack = new HandlerStack($h);
        $stack->push($m);
        $comp = $stack->resolve();
        $p = $comp(new Request('PUT', 'http://www.google.com', [], $bd), []);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\FulfilledPromise', $p);
        $response = $p->wait();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function expectProvider()
    {
        return [
            [true, ['100-Continue']],
            [false, []],
            [10, ['100-Continue']],
            [500000, []]
        ];
    }

    /**
     * @dataProvider expectProvider
     */
    public function testAddsExpect($value, $result)
    {
        $bd = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for(fopen(__DIR__ . '/../composer.json', 'r'));

        $h = new MockHandler([
            function (RequestInterface $request) use ($result) {
                $this->assertEquals($result, $request->getHeader('Expect'));
                return new Response(200);
            }
        ]);

        $m = Middleware::prepareBody();
        $stack = new HandlerStack($h);
        $stack->push($m);
        $comp = $stack->resolve();
        $p = $comp(new Request('PUT', 'http://www.google.com', [], $bd), [
            'expect' => $value
        ]);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\FulfilledPromise', $p);
        $response = $p->wait();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testIgnoresIfExpectIsPresent()
    {
        $bd = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for(fopen(__DIR__ . '/../composer.json', 'r'));
        $h = new MockHandler([
            function (RequestInterface $request) {
                $this->assertEquals(['Foo'], $request->getHeader('Expect'));
                return new Response(200);
            }
        ]);

        $m = Middleware::prepareBody();
        $stack = new HandlerStack($h);
        $stack->push($m);
        $comp = $stack->resolve();
        $p = $comp(
            new Request('PUT', 'http://www.google.com', ['Expect' => 'Foo'], $bd),
            ['expect' => true]
        );
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\FulfilledPromise', $p);
        $response = $p->wait();
        $this->assertEquals(200, $response->getStatusCode());
    }
}