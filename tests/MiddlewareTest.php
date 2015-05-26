<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Cookie\CookieJar;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Cookie\SetCookie;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\MockHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\HandlerStack;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\MessageFormatter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Middleware;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class MiddlewareTest extends \PHPUnit_Framework_TestCase
{
    public function testAddsCookiesToRequests()
    {
        $jar = new CookieJar();
        $m = Middleware::cookies($jar);
        $h = new MockHandler(
            [
                function (RequestInterface $request) {
                    return new Response(200, [
                        'Set-Cookie' => new SetCookie([
                            'Name'   => 'name',
                            'Value'  => 'value',
                            'Domain' => 'foo.com'
                        ])
                    ]);
                }
            ]
        );
        $f = $m($h);
        $f(new Request('GET', 'http://foo.com'), ['cookies' => $jar])->wait();
        $this->assertCount(1, $jar);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ClientException
     */
    public function testThrowsExceptionOnHttpClientError()
    {
        $m = Middleware::httpErrors();
        $h = new MockHandler([new Response(404)]);
        $f = $m($h);
        $p = $f(new Request('GET', 'http://foo.com'), ['http_errors' => true]);
        $this->assertEquals('pending', $p->getState());
        $p->wait();
        $this->assertEquals('rejected', $p->getState());
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ServerException
     */
    public function testThrowsExceptionOnHttpServerError()
    {
        $m = Middleware::httpErrors();
        $h = new MockHandler([new Response(500)]);
        $f = $m($h);
        $p = $f(new Request('GET', 'http://foo.com'), ['http_errors' => true]);
        $this->assertEquals('pending', $p->getState());
        $p->wait();
        $this->assertEquals('rejected', $p->getState());
    }

    public function testTracksHistory()
    {
        $container = [];
        $m = Middleware::history($container);
        $h = new MockHandler([new Response(200), new Response(201)]);
        $f = $m($h);
        $p1 = $f(new Request('GET', 'http://foo.com'), ['headers' => ['foo' => 'bar']]);
        $p2 = $f(new Request('HEAD', 'http://foo.com'), ['headers' => ['foo' => 'baz']]);
        $p1->wait();
        $p2->wait();
        $this->assertCount(2, $container);
        $this->assertEquals(200, $container[0]['response']->getStatusCode());
        $this->assertEquals(201, $container[1]['response']->getStatusCode());
        $this->assertEquals('GET', $container[0]['request']->getMethod());
        $this->assertEquals('HEAD', $container[1]['request']->getMethod());
        $this->assertEquals('bar', $container[0]['options']['headers']['foo']);
        $this->assertEquals('baz', $container[1]['options']['headers']['foo']);
    }

    public function testTracksHistoryForFailures()
    {
        $container = [];
        $m = Middleware::history($container);
        $request = new Request('GET', 'http://foo.com');
        $h = new MockHandler([new RequestException('error', $request)]);
        $f = $m($h);
        $f($request, [])->wait(false);
        $this->assertCount(1, $container);
        $this->assertEquals('GET', $container[0]['request']->getMethod());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException', $container[0]['error']);
    }

    public function testTapsBeforeAndAfter()
    {
        $calls = [];
        $m = function ($handler) use (&$calls) {
            return function ($request, $options) use ($handler, &$calls) {
                $calls[] = '2';
                return $handler($request, $options);
            };
        };

        $m2 = Middleware::tap(
            function (RequestInterface $request, array $options) use (&$calls) {
                $calls[] = '1';
            },
            function (RequestInterface $request, array $options, PromiseInterface $p) use (&$calls) {
                $calls[] = '3';
            }
        );

        $h = new MockHandler([new Response()]);
        $b = new HandlerStack($h);
        $b->push($m2);
        $b->push($m);
        $comp = $b->resolve();
        $p = $comp(new Request('GET', 'http://foo.com'), []);
        $this->assertEquals('123', implode('', $calls));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface', $p);
        $this->assertEquals(200, $p->wait()->getStatusCode());
    }

    public function testMapsRequest()
    {
        $h = new MockHandler([
            function (RequestInterface $request, array $options) {
                $this->assertEquals('foo', $request->getHeaderLine('Bar'));
                return new Response(200);
            }
        ]);
        $stack = new HandlerStack($h);
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            return $request->withHeader('Bar', 'foo');
        }));
        $comp = $stack->resolve();
        $p = $comp(new Request('PUT', 'http://www.google.com'), []);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\FulfilledPromise', $p);
    }

    public function testMapsResponse()
    {
        $h = new MockHandler([new Response(200)]);
        $stack = new HandlerStack($h);
        $stack->push(Middleware::mapResponse(function (ResponseInterface $response) {
            return $response->withHeader('Bar', 'foo');
        }));
        $comp = $stack->resolve();
        $p = $comp(new Request('PUT', 'http://www.google.com'), []);
        $p->wait();
        $this->assertEquals('foo', $p->wait()->getHeaderLine('Bar'));
    }

    public function testLogsRequestsAndResponses()
    {
        $h = new MockHandler([new Response(200)]);
        $stack = new HandlerStack($h);
        $logger = new Logger();
        $formatter = new MessageFormatter();
        $stack->push(Middleware::log($logger, $formatter));
        $comp = $stack->resolve();
        $p = $comp(new Request('PUT', 'http://www.google.com'), []);
        $p->wait();
        $this->assertContains('"PUT / HTTP/1.1" 200', $logger->output);
    }

    public function testLogsRequestsAndErrors()
    {
        $h = new MockHandler([new Response(404)]);
        $stack = new HandlerStack($h);
        $logger = new Logger();
        $formatter = new MessageFormatter('"{method} {target} HTTP/{version}\" {code} {error}');
        $stack->push(Middleware::log($logger, $formatter));
        $stack->push(Middleware::httpErrors());
        $comp = $stack->resolve();
        $p = $comp(new Request('PUT', 'http://www.google.com'), ['http_errors' => true]);
        $p->wait(false);
        $this->assertContains('"PUT / HTTP/1.1\" 404 Client error: 404', $logger->output);
    }
}

/**
 * @internal
 */
class Logger implements LoggerInterface
{
    use LoggerTrait;
    public $output;

    public function log($level, $message, array $context = [])
    {
        $this->output .= $message . "\n";
    }
}