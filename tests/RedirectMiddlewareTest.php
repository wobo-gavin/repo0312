<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\MockHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\HandlerStack;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Middleware;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RedirectMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\RedirectMiddleware
 */
class RedirectMiddlewareTest extends TestCase
{
    public function testIgnoresNonRedirects()
    {
        $response = new Response(200);
        $stack = new HandlerStack(new MockHandler([$response]));
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com');
        $promise = $handler($request, []);
        $response = $promise->wait();
        self::assertSame(200, $response->getStatusCode());
    }

    public function testIgnoresWhenNoLocation()
    {
        $response = new Response(304);
        $stack = new HandlerStack(new MockHandler([$response]));
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com');
        $promise = $handler($request, []);
        $response = $promise->wait();
        self::assertSame(304, $response->getStatusCode());
    }

    public function testRedirectsWithAbsoluteUri()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://test.com']),
            new Response(200)
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $promise = $handler($request, [
            'allow_redirects' => ['max' => 2]
        ]);
        $response = $promise->wait();
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('http://test.com', (string)$mock->getLastRequest()->getUri());
    }

    public function testRedirectsWithRelativeUri()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => '/foo']),
            new Response(200)
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $promise = $handler($request, [
            'allow_redirects' => ['max' => 2]
        ]);
        $response = $promise->wait();
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('http://example.com/foo', (string)$mock->getLastRequest()->getUri());
    }

    public function testLimitsToMaxRedirects()
    {
        $mock = new MockHandler([
            new Response(301, ['Location' => 'http://test.com']),
            new Response(302, ['Location' => 'http://test.com']),
            new Response(303, ['Location' => 'http://test.com']),
            new Response(304, ['Location' => 'http://test.com'])
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com');
        $promise = $handler($request, ['allow_redirects' => ['max' => 3]]);

        $this->expectException(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\TooManyRedirectsException::class);
        $this->expectExceptionMessage('Will not follow more than 3 redirects');
        $promise->wait();
    }

    public function testEnsuresProtocolIsValid()
    {
        $mock = new MockHandler([
            new Response(301, ['Location' => 'ftp://test.com'])
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com');

        $this->expectException(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\BadResponseException::class);
        $this->expectExceptionMessage('Redirect URI,');
        $handler($request, ['allow_redirects' => ['max' => 3]])->wait();
    }

    public function testAddsRefererHeader()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://test.com']),
            new Response(200)
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $promise = $handler($request, [
            'allow_redirects' => ['max' => 2, 'referer' => true]
        ]);
        $promise->wait();
        self::assertSame(
            'http://example.com?a=b',
            $mock->getLastRequest()->getHeaderLine('Referer')
        );
    }

    public function testAddsRefererHeaderButClearsUserInfo()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://test.com']),
            new Response(200)
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://foo:bar@example.com?a=b');
        $promise = $handler($request, [
            'allow_redirects' => ['max' => 2, 'referer' => true]
        ]);
        $promise->wait();
        self::assertSame(
            'http://example.com?a=b',
            $mock->getLastRequest()->getHeaderLine('Referer')
        );
    }

    public function testAdds/* Replaced /* Replaced /* Replaced Guzzle */ */ */RedirectHeader()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://example.com']),
            new Response(302, ['Location' => 'http://example.com/foo']),
            new Response(302, ['Location' => 'http://example.com/bar']),
            new Response(200)
        ]);

        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $promise = $handler($request, [
            'allow_redirects' => ['track_redirects' => true]
        ]);
        $response = $promise->wait(true);
        self::assertSame(
            [
                'http://example.com',
                'http://example.com/foo',
                'http://example.com/bar',
            ],
            $response->getHeader(RedirectMiddleware::HISTORY_HEADER)
        );
    }

    public function testAdds/* Replaced /* Replaced /* Replaced Guzzle */ */ */RedirectStatusHeader()
    {
        $mock = new MockHandler([
            new Response(301, ['Location' => 'http://example.com']),
            new Response(302, ['Location' => 'http://example.com/foo']),
            new Response(301, ['Location' => 'http://example.com/bar']),
            new Response(302, ['Location' => 'http://example.com/baz']),
            new Response(200)
        ]);

        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $promise = $handler($request, [
            'allow_redirects' => ['track_redirects' => true]
        ]);
        $response = $promise->wait(true);
        self::assertSame(
            [
                '301',
                '302',
                '301',
                '302',
            ],
            $response->getHeader(RedirectMiddleware::STATUS_HISTORY_HEADER)
        );
    }

    public function testDoesNotAddRefererWhenGoingFromHttpsToHttp()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://test.com']),
            new Response(200)
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'https://example.com?a=b');
        $promise = $handler($request, [
            'allow_redirects' => ['max' => 2, 'referer' => true]
        ]);
        $promise->wait();
        self::assertFalse($mock->getLastRequest()->hasHeader('Referer'));
    }

    public function testInvokesOnRedirectForRedirects()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://test.com']),
            new Response(200)
        ]);
        $stack = new HandlerStack($mock);
        $stack->push(Middleware::redirect());
        $handler = $stack->resolve();
        $request = new Request('GET', 'http://example.com?a=b');
        $call = false;
        $promise = $handler($request, [
            'allow_redirects' => [
                'max' => 2,
                'on_redirect' => function ($request, $response, $uri) use (&$call) {
                    self::assertSame(302, $response->getStatusCode());
                    self::assertSame('GET', $request->getMethod());
                    self::assertSame('http://test.com', (string) $uri);
                    $call = true;
                }
            ]
        ]);
        $promise->wait();
        self::assertTrue($call);
    }

    public function testRemoveAuthorizationHeaderOnRedirect()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://test.com']),
            function (RequestInterface $request) {
                self::assertFalse($request->hasHeader('Authorization'));
                return new Response(200);
            }
        ]);
        $handler = HandlerStack::create($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['handler' => $handler]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://example.com?a=b', ['auth' => ['testuser', 'testpass']]);
    }

    public function testNotRemoveAuthorizationHeaderOnRedirect()
    {
        $mock = new MockHandler([
            new Response(302, ['Location' => 'http://example.com/2']),
            function (RequestInterface $request) {
                self::assertTrue($request->hasHeader('Authorization'));
                return new Response(200);
            }
        ]);
        $handler = HandlerStack::create($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['handler' => $handler]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://example.com?a=b', ['auth' => ['testuser', 'testpass']]);
    }
}
