<?php

namespace /* Replaced Guzzle */Http\Tests;

use /* Replaced Guzzle */Http\Client;
use /* Replaced Guzzle */Http\Cookie\CookieJar;
use /* Replaced Guzzle */Http\Handler\MockHandler;
use /* Replaced Guzzle */Http\HandlerStack;
use /* Replaced Guzzle */Http\Middleware;
use /* Replaced Guzzle */Http\Promise\PromiseInterface;
use /* Replaced Guzzle */Http\/* Replaced Psr7 */;
use /* Replaced Guzzle */Http\/* Replaced Psr7 */\Request;
use /* Replaced Guzzle */Http\/* Replaced Psr7 */\Response;
use /* Replaced Guzzle */Http\/* Replaced Psr7 */\Uri;
use /* Replaced Guzzle */Http\RequestOptions;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ClientTest extends TestCase
{
    public function testUsesDefaultHandler()
    {
        $/* Replaced client */ = new Client();
        Server::enqueue([new Response(200, ['Content-Length' => 0])]);
        $response = $/* Replaced client */->get(Server::$url);
        self::assertSame(200, $response->getStatusCode());
    }

    public function testValidatesArgsForMagicMethods()
    {
        $/* Replaced client */ = new Client();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Magic request methods require a URI and optional options array');
        $/* Replaced client */->options();
    }

    public function testCanSendAsyncGetRequests()
    {
        $/* Replaced client */ = new Client();
        Server::flush();
        Server::enqueue([new Response(200, ['Content-Length' => 2], 'hi')]);
        $p = $/* Replaced client */->getAsync(Server::$url, ['query' => ['test' => 'foo']]);
        self::assertInstanceOf(PromiseInterface::class, $p);
        self::assertSame(200, $p->wait()->getStatusCode());
        $received = Server::received(true);
        self::assertCount(1, $received);
        self::assertSame('test=foo', $received[0]->getUri()->getQuery());
    }

    public function testCanSendSynchronously()
    {
        $/* Replaced client */ = new Client(['handler' => new MockHandler([new Response()])]);
        $request = new Request('GET', 'http://example.com');
        $r = $/* Replaced client */->send($request);
        self::assertInstanceOf(ResponseInterface::class, $r);
        self::assertSame(200, $r->getStatusCode());
    }

    public function testClientHasOptions()
    {
        $/* Replaced client */ = new Client([
            'base_uri' => 'http://foo.com',
            'timeout'  => 2,
            'headers'  => ['bar' => 'baz'],
            'handler'  => new MockHandler()
        ]);
        $config = Helpers::readObjectAttribute($/* Replaced client */, 'config');
        self::assertArrayHasKey('base_uri', $config);
        self::assertInstanceOf(Uri::class, $config['base_uri']);
        self::assertSame('http://foo.com', (string) $config['base_uri']);
        self::assertArrayHasKey('handler', $config);
        self::assertNotNull($config['handler']);
        self::assertArrayHasKey('timeout', $config);
        self::assertSame(2, $config['timeout']);
    }

    public function testCanMergeOnBaseUri()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client([
            'base_uri' => 'http://foo.com/bar/',
            'handler'  => $mock
        ]);
        $/* Replaced client */->get('baz');
        self::assertSame(
            'http://foo.com/bar/baz',
            (string)$mock->getLastRequest()->getUri()
        );
    }

    public function testCanMergeOnBaseUriWithRequest()
    {
        $mock = new MockHandler([new Response(), new Response()]);
        $/* Replaced client */ = new Client([
            'handler'  => $mock,
            'base_uri' => 'http://foo.com/bar/'
        ]);
        $/* Replaced client */->request('GET', new Uri('baz'));
        self::assertSame(
            'http://foo.com/bar/baz',
            (string) $mock->getLastRequest()->getUri()
        );

        $/* Replaced client */->request('GET', new Uri('baz'), ['base_uri' => 'http://example.com/foo/']);
        self::assertSame(
            'http://example.com/foo/baz',
            (string) $mock->getLastRequest()->getUri(),
            'Can overwrite the base_uri through the request options'
        );
    }

    public function testCanUseRelativeUriWithSend()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client([
            'handler'  => $mock,
            'base_uri' => 'http://bar.com'
        ]);
        $config = Helpers::readObjectAttribute($/* Replaced client */, 'config');
        self::assertSame('http://bar.com', (string) $config['base_uri']);
        $request = new Request('GET', '/baz');
        $/* Replaced client */->send($request);
        self::assertSame(
            'http://bar.com/baz',
            (string) $mock->getLastRequest()->getUri()
        );
    }

    public function testMergesDefaultOptionsAndDoesNotOverwriteUa()
    {
        $/* Replaced client */ = new Client(['headers' => ['User-agent' => 'foo']]);
        $config = Helpers::readObjectAttribute($/* Replaced client */, 'config');
        self::assertSame(['User-agent' => 'foo'], $config['headers']);
        self::assertIsArray($config['allow_redirects']);
        self::assertTrue($config['http_errors']);
        self::assertTrue($config['decode_content']);
        self::assertTrue($config['verify']);
    }

    public function testDoesNotOverwriteHeaderWithDefault()
    {
        $mock = new MockHandler([new Response()]);
        $c = new Client([
            'headers' => ['User-agent' => 'foo'],
            'handler' => $mock
        ]);
        $c->get('http://example.com', ['headers' => ['User-Agent' => 'bar']]);
        self::assertSame('bar', $mock->getLastRequest()->getHeaderLine('User-Agent'));
    }

    public function testDoesNotOverwriteHeaderWithDefaultInRequest()
    {
        $mock = new MockHandler([new Response()]);
        $c = new Client([
            'headers' => ['User-agent' => 'foo'],
            'handler' => $mock
        ]);
        $request = new Request('GET', Server::$url, ['User-Agent' => 'bar']);
        $c->send($request);
        self::assertSame('bar', $mock->getLastRequest()->getHeaderLine('User-Agent'));
    }

    public function testDoesOverwriteHeaderWithSetRequestOption()
    {
        $mock = new MockHandler([new Response()]);
        $c = new Client([
            'headers' => ['User-agent' => 'foo'],
            'handler' => $mock
        ]);
        $request = new Request('GET', Server::$url, ['User-Agent' => 'bar']);
        $c->send($request, ['headers' => ['User-Agent' => 'YO']]);
        self::assertSame('YO', $mock->getLastRequest()->getHeaderLine('User-Agent'));
    }

    public function testCanUnsetRequestOptionWithNull()
    {
        $mock = new MockHandler([new Response()]);
        $c = new Client([
            'headers' => ['foo' => 'bar'],
            'handler' => $mock
        ]);
        $c->get('http://example.com', ['headers' => null]);
        self::assertFalse($mock->getLastRequest()->hasHeader('foo'));
    }

    public function testAllowRedirectsCanBeTrue()
    {
        $mock = new MockHandler([new Response(200, [], 'foo')]);
        $handler = HandlerStack::create($mock);
        $/* Replaced client */ = new Client(['handler' => $handler]);
        $/* Replaced client */->get('http://foo.com', ['allow_redirects' => true]);
        self::assertIsArray($mock->getLastOptions()['allow_redirects']);
    }

    public function testValidatesAllowRedirects()
    {
        $mock = new MockHandler([new Response(200, [], 'foo')]);
        $handler = HandlerStack::create($mock);
        $/* Replaced client */ = new Client(['handler' => $handler]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('allow_redirects must be true, false, or array');
        $/* Replaced client */->get('http://foo.com', ['allow_redirects' => 'foo']);
    }

    public function testThrowsHttpErrorsByDefault()
    {
        $mock = new MockHandler([new Response(404)]);
        $handler = HandlerStack::create($mock);
        $/* Replaced client */ = new Client(['handler' => $handler]);

        $this->expectException(\/* Replaced Guzzle */Http\Exception\ClientException::class);
        $/* Replaced client */->get('http://foo.com');
    }

    public function testValidatesCookies()
    {
        $mock = new MockHandler([new Response(200, [], 'foo')]);
        $handler = HandlerStack::create($mock);
        $/* Replaced client */ = new Client(['handler' => $handler]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('cookies must be an instance of /* Replaced Guzzle */Http\\Cookie\\CookieJarInterface');
        $/* Replaced client */->get('http://foo.com', ['cookies' => 'foo']);
    }

    public function testSetCookieToTrueUsesSharedJar()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'foo=bar']),
            new Response()
        ]);
        $handler = HandlerStack::create($mock);
        $/* Replaced client */ = new Client(['handler' => $handler, 'cookies' => true]);
        $/* Replaced client */->get('http://foo.com');
        $/* Replaced client */->get('http://foo.com');
        self::assertSame('foo=bar', $mock->getLastRequest()->getHeaderLine('Cookie'));
    }

    public function testSetCookieToJar()
    {
        $mock = new MockHandler([
            new Response(200, ['Set-Cookie' => 'foo=bar']),
            new Response()
        ]);
        $handler = HandlerStack::create($mock);
        $/* Replaced client */ = new Client(['handler' => $handler]);
        $jar = new CookieJar();
        $/* Replaced client */->get('http://foo.com', ['cookies' => $jar]);
        $/* Replaced client */->get('http://foo.com', ['cookies' => $jar]);
        self::assertSame('foo=bar', $mock->getLastRequest()->getHeaderLine('Cookie'));
    }

    public function testCanDisableContentDecoding()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->get('http://foo.com', ['decode_content' => false]);
        $last = $mock->getLastRequest();
        self::assertFalse($last->hasHeader('Accept-Encoding'));
        self::assertFalse($mock->getLastOptions()['decode_content']);
    }

    public function testCanSetContentDecodingToValue()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->get('http://foo.com', ['decode_content' => 'gzip']);
        $last = $mock->getLastRequest();
        self::assertSame('gzip', $last->getHeaderLine('Accept-Encoding'));
        self::assertSame('gzip', $mock->getLastOptions()['decode_content']);
    }

    public function testAddsAcceptEncodingbyCurl()
    {
        $/* Replaced client */ = new Client(['curl' => [\CURLOPT_ENCODING => '']]);

        Server::flush();
        Server::enqueue([new Response()]);
        $/* Replaced client */->get(Server::$url);
        $sent = Server::received()[0];
        self::assertTrue($sent->hasHeader('Accept-Encoding'));

        $mock = new MockHandler([new Response()]);
        $/* Replaced client */->get('http://foo.com', ['handler' => $mock]);
        self::assertSame([\CURLOPT_ENCODING => ''], $mock->getLastOptions()['curl']);
    }

    public function testValidatesHeaders()
    {
        $mock = new MockHandler();
        $/* Replaced client */ = new Client(['handler' => $mock]);

        $this->expectException(\InvalidArgumentException::class);
        $/* Replaced client */->get('http://foo.com', ['headers' => 'foo']);
    }

    public function testAddsBody()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('PUT', 'http://foo.com');
        $/* Replaced client */->send($request, ['body' => 'foo']);
        $last = $mock->getLastRequest();
        self::assertSame('foo', (string) $last->getBody());
    }

    public function testValidatesQuery()
    {
        $mock = new MockHandler();
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('PUT', 'http://foo.com');

        $this->expectException(\InvalidArgumentException::class);
        $/* Replaced client */->send($request, ['query' => false]);
    }

    public function testQueryCanBeString()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('PUT', 'http://foo.com');
        $/* Replaced client */->send($request, ['query' => 'foo']);
        self::assertSame('foo', $mock->getLastRequest()->getUri()->getQuery());
    }

    public function testQueryCanBeArray()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('PUT', 'http://foo.com');
        $/* Replaced client */->send($request, ['query' => ['foo' => 'bar baz']]);
        self::assertSame('foo=bar%20baz', $mock->getLastRequest()->getUri()->getQuery());
    }

    public function testCanAddJsonData()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('PUT', 'http://foo.com');
        $/* Replaced client */->send($request, ['json' => ['foo' => 'bar']]);
        $last = $mock->getLastRequest();
        self::assertSame('{"foo":"bar"}', (string) $mock->getLastRequest()->getBody());
        self::assertSame('application/json', $last->getHeaderLine('Content-Type'));
    }

    public function testCanAddJsonDataWithoutOverwritingContentType()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('PUT', 'http://foo.com');
        $/* Replaced client */->send($request, [
            'headers' => ['content-type' => 'foo'],
            'json'    => 'a'
        ]);
        $last = $mock->getLastRequest();
        self::assertSame('"a"', (string) $mock->getLastRequest()->getBody());
        self::assertSame('foo', $last->getHeaderLine('Content-Type'));
    }

    public function testCanAddJsonDataWithNullHeader()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('PUT', 'http://foo.com');
        $/* Replaced client */->send($request, [
            'headers' => null,
            'json'    => 'a'
        ]);
        $last = $mock->getLastRequest();
        self::assertSame('"a"', (string) $mock->getLastRequest()->getBody());
        self::assertSame('application/json', $last->getHeaderLine('Content-Type'));
    }

    public function testAuthCanBeTrue()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->get('http://foo.com', ['auth' => false]);
        $last = $mock->getLastRequest();
        self::assertFalse($last->hasHeader('Authorization'));
    }

    public function testAuthCanBeArrayForBasicAuth()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->get('http://foo.com', ['auth' => ['a', 'b']]);
        $last = $mock->getLastRequest();
        self::assertSame('Basic YTpi', $last->getHeaderLine('Authorization'));
    }

    public function testAuthCanBeArrayForDigestAuth()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->get('http://foo.com', ['auth' => ['a', 'b', 'digest']]);
        $last = $mock->getLastOptions();
        self::assertSame([
            \CURLOPT_HTTPAUTH => 2,
            \CURLOPT_USERPWD  => 'a:b'
        ], $last['curl']);
    }

    public function testAuthCanBeArrayForNtlmAuth()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->get('http://foo.com', ['auth' => ['a', 'b', 'ntlm']]);
        $last = $mock->getLastOptions();
        self::assertSame([
            \CURLOPT_HTTPAUTH => 8,
            \CURLOPT_USERPWD  => 'a:b'
        ], $last['curl']);
    }

    public function testAuthCanBeCustomType()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->get('http://foo.com', ['auth' => 'foo']);
        $last = $mock->getLastOptions();
        self::assertSame('foo', $last['auth']);
    }

    public function testCanAddFormParams()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->post('http://foo.com', [
            'form_params' => [
                'foo' => 'bar bam',
                'baz' => ['boo' => 'qux']
            ]
        ]);
        $last = $mock->getLastRequest();
        self::assertSame(
            'application/x-www-form-urlencoded',
            $last->getHeaderLine('Content-Type')
        );
        self::assertSame(
            'foo=bar+bam&baz%5Bboo%5D=qux',
            (string) $last->getBody()
        );
    }

    public function testFormParamsEncodedProperly()
    {
        $separator = \ini_get('arg_separator.output');
        \ini_set('arg_separator.output', '&amp;');
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->post('http://foo.com', [
            'form_params' => [
                'foo' => 'bar bam',
                'baz' => ['boo' => 'qux']
            ]
        ]);
        $last = $mock->getLastRequest();
        self::assertSame(
            'foo=bar+bam&baz%5Bboo%5D=qux',
            (string) $last->getBody()
        );

        \ini_set('arg_separator.output', $separator);
    }

    public function testEnsuresThatFormParamsAndMultipartAreExclusive()
    {
        $/* Replaced client */ = new Client(['handler' => static function () {
        }]);

        $this->expectException(\InvalidArgumentException::class);
        $/* Replaced client */->post('http://foo.com', [
            'form_params' => ['foo' => 'bar bam'],
            'multipart' => []
        ]);
    }

    public function testCanSendMultipart()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->post('http://foo.com', [
            'multipart' => [
                [
                    'name'     => 'foo',
                    'contents' => 'bar'
                ],
                [
                    'name'     => 'test',
                    'contents' => \fopen(__FILE__, 'r')
                ]
            ]
        ]);

        $last = $mock->getLastRequest();
        self::assertStringContainsString(
            'multipart/form-data; boundary=',
            $last->getHeaderLine('Content-Type')
        );

        self::assertStringContainsString(
            'Content-Disposition: form-data; name="foo"',
            (string) $last->getBody()
        );

        self::assertStringContainsString('bar', (string) $last->getBody());
        self::assertStringContainsString(
            'Content-Disposition: form-data; name="foo"' . "\r\n",
            (string) $last->getBody()
        );
        self::assertStringContainsString(
            'Content-Disposition: form-data; name="test"; filename="ClientTest.php"',
            (string) $last->getBody()
        );
    }

    public function testCanSendMultipartWithExplicitBody()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->send(
            new Request(
                'POST',
                'http://foo.com',
                [],
                new /* Replaced Psr7 */\MultipartStream(
                    [
                        [
                            'name' => 'foo',
                            'contents' => 'bar',
                        ],
                        [
                            'name' => 'test',
                            'contents' => \fopen(__FILE__, 'r'),
                        ],
                    ]
                )
            )
        );

        $last = $mock->getLastRequest();
        self::assertStringContainsString(
            'multipart/form-data; boundary=',
            $last->getHeaderLine('Content-Type')
        );

        self::assertStringContainsString(
            'Content-Disposition: form-data; name="foo"',
            (string) $last->getBody()
        );

        self::assertStringContainsString('bar', (string) $last->getBody());
        self::assertStringContainsString(
            'Content-Disposition: form-data; name="foo"' . "\r\n",
            (string) $last->getBody()
        );
        self::assertStringContainsString(
            'Content-Disposition: form-data; name="test"; filename="ClientTest.php"',
            (string) $last->getBody()
        );
    }

    public function testUsesProxyEnvironmentVariables()
    {
        unset($_SERVER['HTTP_PROXY'], $_SERVER['HTTPS_PROXY'], $_SERVER['NO_PROXY']);
        \putenv('HTTP_PROXY=');
        \putenv('HTTPS_PROXY=');
        \putenv('NO_PROXY=');

        try {
            $/* Replaced client */ = new Client();
            $config = Helpers::readObjectAttribute($/* Replaced client */, 'config');
            self::assertArrayNotHasKey('proxy', $config);

            \putenv('HTTP_PROXY=127.0.0.1');
            $/* Replaced client */ = new Client();
            $config = Helpers::readObjectAttribute($/* Replaced client */, 'config');
            self::assertArrayHasKey('proxy', $config);
            self::assertSame(['http' => '127.0.0.1'], $config['proxy']);

            \putenv('HTTPS_PROXY=127.0.0.2');
            \putenv('NO_PROXY=127.0.0.3, 127.0.0.4');
            $/* Replaced client */ = new Client();
            $config = Helpers::readObjectAttribute($/* Replaced client */, 'config');
            self::assertArrayHasKey('proxy', $config);
            self::assertSame(
                ['http' => '127.0.0.1', 'https' => '127.0.0.2', 'no' => ['127.0.0.3','127.0.0.4']],
                $config['proxy']
            );
        } finally {
            \putenv('HTTP_PROXY=');
            \putenv('HTTPS_PROXY=');
            \putenv('NO_PROXY=');
        }
    }

    public function testRequestSendsWithSync()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->request('GET', 'http://foo.com');
        self::assertTrue($mock->getLastOptions()['synchronous']);
    }

    public function testSendSendsWithSync()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $/* Replaced client */->send(new Request('GET', 'http://foo.com'));
        self::assertTrue($mock->getLastOptions()['synchronous']);
    }

    public function testCanSetCustomHandler()
    {
        $mock = new MockHandler([new Response(500)]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $mock2 = new MockHandler([new Response(200)]);
        self::assertSame(
            200,
            $/* Replaced client */->send(new Request('GET', 'http://foo.com'), [
                'handler' => $mock2
            ])->getStatusCode()
        );
    }

    public function testProperlyBuildsQuery()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('PUT', 'http://foo.com');
        $/* Replaced client */->send($request, ['query' => ['foo' => 'bar', 'john' => 'doe']]);
        self::assertSame('foo=bar&john=doe', $mock->getLastRequest()->getUri()->getQuery());
    }

    public function testSendSendsWithIpAddressAndPortAndHostHeaderInRequestTheHostShouldBePreserved()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['base_uri' => 'http://127.0.0.1:8585', 'handler' => $mockHandler]);
        $request = new Request('GET', '/test', ['Host' => 'foo.com']);

        $/* Replaced client */->send($request);

        self::assertSame('foo.com', $mockHandler->getLastRequest()->getHeader('Host')[0]);
    }

    public function testSendSendsWithDomainAndHostHeaderInRequestTheHostShouldBePreserved()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['base_uri' => 'http://foo2.com', 'handler' => $mockHandler]);
        $request = new Request('GET', '/test', ['Host' => 'foo.com']);

        $/* Replaced client */->send($request);

        self::assertSame('foo.com', $mockHandler->getLastRequest()->getHeader('Host')[0]);
    }

    public function testValidatesSink()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mockHandler]);

        $this->expectException(\InvalidArgumentException::class);
        $/* Replaced client */->get('http://test.com', ['sink' => true]);
    }

    public function testHttpDefaultSchemeIfUriHasNone()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mockHandler]);

        $/* Replaced client */->request('GET', '//example.org/test');

        self::assertSame('http://example.org/test', (string) $mockHandler->getLastRequest()->getUri());
    }

    public function testOnlyAddSchemeWhenHostIsPresent()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mockHandler]);

        $/* Replaced client */->request('GET', 'baz');
        self::assertSame(
            'baz',
            (string) $mockHandler->getLastRequest()->getUri()
        );
    }

    public function testHandlerIsCallable()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Client(['handler' => 'not_cllable']);
    }

    public function testResponseBodyAsString()
    {
        $responseBody = '{ "package": "/* Replaced guzzle */" }';
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $responseBody)]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('GET', 'http://foo.com');
        $response = $/* Replaced client */->send($request, ['json' => ['a' => 'b']]);

        self::assertSame($responseBody, (string) $response->getBody());
    }

    public function testResponseContent()
    {
        $responseBody = '{ "package": "/* Replaced guzzle */" }';
        $mock = new MockHandler([new Response(200, ['Content-Type' => 'application/json'], $responseBody)]);
        $/* Replaced client */ = new Client(['handler' => $mock]);
        $request = new Request('POST', 'http://foo.com');
        $response = $/* Replaced client */->send($request, ['json' => ['a' => 'b']]);

        self::assertSame($responseBody, $response->getBody()->getContents());
    }

    public function testIdnSupportDefaultValue()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mockHandler]);

        $config = Helpers::readObjectAttribute($/* Replaced client */, 'config');

        self::assertFalse($config['idn_conversion']);
    }

    /**
     * @requires extension idn
     */
    public function testIdnIsTranslatedToAsciiWhenConversionIsEnabled()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mockHandler]);

        $/* Replaced client */->request('GET', 'https://яндекс.рф/images', ['idn_conversion' => true]);

        $request = $mockHandler->getLastRequest();

        self::assertSame('https://xn--d1acpjx3f.xn--p1ai/images', (string) $request->getUri());
        self::assertSame('xn--d1acpjx3f.xn--p1ai', (string) $request->getHeaderLine('Host'));
    }

    public function testIdnStaysTheSameWhenConversionIsDisabled()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mockHandler]);

        $/* Replaced client */->request('GET', 'https://яндекс.рф/images', ['idn_conversion' => false]);

        $request = $mockHandler->getLastRequest();

        self::assertSame('https://яндекс.рф/images', (string) $request->getUri());
        self::assertSame('яндекс.рф', (string) $request->getHeaderLine('Host'));
    }

    /**
     * @requires extension idn
     */
    public function testExceptionOnInvalidIdn()
    {
        $mockHandler = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client(['handler' => $mockHandler]);

        $this->expectException(\/* Replaced Guzzle */Http\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('IDN conversion failed');
        $/* Replaced client */->request('GET', 'https://-яндекс.рф/images', ['idn_conversion' => true]);
    }

    /**
     * @depends testCanUseRelativeUriWithSend
     * @requires extension idn
     */
    public function testIdnBaseUri()
    {
        $mock = new MockHandler([new Response()]);
        $/* Replaced client */ = new Client([
            'handler'  => $mock,
            'base_uri' => 'http://яндекс.рф',
            'idn_conversion' => true,
        ]);
        $config = Helpers::readObjectAttribute($/* Replaced client */, 'config');
        self::assertSame('http://яндекс.рф', (string) $config['base_uri']);
        $request = new Request('GET', '/baz');
        $/* Replaced client */->send($request);
        self::assertSame('http://xn--d1acpjx3f.xn--p1ai/baz', (string) $mock->getLastRequest()->getUri());
        self::assertSame('xn--d1acpjx3f.xn--p1ai', (string) $mock->getLastRequest()->getHeaderLine('Host'));
    }

    /**
     * @requires extension idn
     */
    public function testIdnWithRedirect()
    {
        $mockHandler = new MockHandler([
            new Response(302, ['Location' => 'http://www.tést.com/whatever']),
            new Response()
        ]);
        $handler = HandlerStack::create($mockHandler);
        $requests = [];
        $handler->push(Middleware::history($requests));
        $/* Replaced client */ = new Client(['handler' => $handler]);

        $/* Replaced client */->request('GET', 'https://яндекс.рф/images', [
            RequestOptions::ALLOW_REDIRECTS => [
                'referer' => true,
                'track_redirects' => true
            ],
            'idn_conversion' => true
        ]);

        $request = $mockHandler->getLastRequest();

        self::assertSame('http://www.xn--tst-bma.com/whatever', (string) $request->getUri());
        self::assertSame('www.xn--tst-bma.com', (string) $request->getHeaderLine('Host'));

        $request = $requests[0]['request'];
        self::assertSame('https://xn--d1acpjx3f.xn--p1ai/images', (string) $request->getUri());
        self::assertSame('xn--d1acpjx3f.xn--p1ai', (string) $request->getHeaderLine('Host'));
    }
}
