<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\FakeParallelAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\History;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testProvidesDefaultUserAgent()
    {
        $this->assertEquals(1, preg_match('#^/* Replaced /* Replaced /* Replaced Guzzle */ */ *//.+ curl/.+ PHP/.+$#', Client::getDefaultUserAgent()));
    }

    public function testUsesDefaultDefaultOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('allow_redirects'));
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('exceptions'));
        $this->assertContains('cacert.pem', $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('verify'));
    }

    public function testUsesProvidedDefaultOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'defaults' => [
                'allow_redirects' => false,
                'query' => ['foo' => 'bar']
            ]
        ]);
        $this->assertFalse($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('allow_redirects'));
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('exceptions'));
        $this->assertContains('cacert.pem', $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('verify'));
        $this->assertEquals(['foo' => 'bar'], $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('query'));
    }

    public function testCanSpecifyBaseUrl()
    {
        $this->assertSame('', (new Client())->getBaseUrl());
        $this->assertEquals('http://foo', (new Client([
            'base_url' => 'http://foo'
        ]))->getBaseUrl());
    }

    public function testCanSpecifyBaseUrlUriTemplate()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => ['http://foo.com/{var}/', ['var' => 'baz']]]);
        $this->assertEquals('http://foo.com/baz/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
    }

    public function testClientUsesDefaultAdapterWhenNoneIsSet()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        if (!extension_loaded('curl')) {
            $adapter = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\StreamAdapter';
        } elseif (ini_get('allow_url_fopen')) {
            $adapter = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\StreamingProxyAdapter';
        } else {
            $adapter = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\CurlAdapter';
        }
        $this->assertInstanceOf($adapter, $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'adapter'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Foo
     */
    public function testCanSpecifyAdapter()
    {
        $adapter = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\AdapterInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $adapter->expects($this->once())
            ->method('send')
            ->will($this->throwException(new \Exception('Foo')));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httpbin.org');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Foo
     */
    public function testCanSpecifyMessageFactory()
    {
        $factory = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface')
            ->setMethods(['createRequest'])
            ->getMockForAbstractClass();
        $factory->expects($this->once())
            ->method('createRequest')
            ->will($this->throwException(new \Exception('Foo')));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['message_factory' => $factory]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get();
    }

    public function testCanSpecifyEmitter()
    {
        $emitter = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\EmitterInterface')
            ->setMethods(['listeners'])
            ->getMockForAbstractClass();
        $emitter->expects($this->once())
            ->method('listeners')
            ->will($this->returnValue('foo'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['emitter' => $emitter]);
        $this->assertEquals('foo', $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->listeners());
    }

    public function testAddsDefaultUserAgentHeaderWithDefaultOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['defaults' => ['allow_redirects' => false]]);
        $this->assertFalse($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('allow_redirects'));
        $this->assertEquals(
            ['User-Agent' => Client::getDefaultUserAgent()],
            $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('headers')
        );
    }

    public function testAddsDefaultUserAgentHeaderWithoutDefaultOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $this->assertEquals(
            ['User-Agent' => Client::getDefaultUserAgent()],
            $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('headers')
        );
    }

    private function getRequestClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client')
            ->setMethods(['send'])
            ->getMock();
        $/* Replaced /* Replaced /* Replaced client */ */ */->expects($this->once())
            ->method('send')
            ->will($this->returnArgument(0));

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    public function requestMethodProvider()
    {
        return [
            ['GET', false],
            ['HEAD', false],
            ['DELETE', false],
            ['OPTIONS', false],
            ['POST', 'foo'],
            ['PUT', 'foo'],
            ['PATCH', 'foo']
        ];
    }

    /**
     * @dataProvider requestMethodProvider
     */
    public function testClientProvidesMethodShortcut($method, $body)
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getRequestClient();
        if ($body) {
            $request = $/* Replaced /* Replaced /* Replaced client */ */ */->{$method}('http://foo.com', [
                'headers' => ['X-Baz' => 'Bar'],
                'body' => $body,
                'query' => ['a' => 'b']
            ]);
        } else {
            $request = $/* Replaced /* Replaced /* Replaced client */ */ */->{$method}('http://foo.com', [
                'headers' => ['X-Baz' => 'Bar'],
                'query' => ['a' => 'b']
            ]);
        }
        $this->assertEquals($method, $request->getMethod());
        $this->assertEquals('Bar', $request->getHeader('X-Baz'));
        $this->assertEquals('a=b', $request->getQuery());
        if ($body) {
            $this->assertEquals($body, $request->getBody());
        }
    }

    public function testClientMergesDefaultOptionsWithRequestOptions()
    {
        $f = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface')
            ->setMethods(array('createRequest'))
            ->getMockForAbstractClass();

        $o = null;
        // Intercept the creation
        $f->expects($this->once())
            ->method('createRequest')
            ->will($this->returnCallback(
                function ($method, $url, array $options = []) use (&$o) {
                    $o = $options;
                    return (new MessageFactory())->createRequest($method, $url, $options);
                }
            ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'message_factory' => $f,
            'defaults' => [
                'headers' => ['Foo' => 'Bar'],
                'query' => ['baz' => 'bam'],
                'exceptions' => false
            ]
        ]);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com?a=b', [
            'headers' => ['Hi' => 'there', '1' => 'one'],
            'allow_redirects' => false,
            'query' => ['t' => 1]
        ]);

        $this->assertFalse($o['allow_redirects']);
        $this->assertFalse($o['exceptions']);
        $this->assertEquals('Bar', $request->getHeader('Foo'));
        $this->assertEquals('there', $request->getHeader('Hi'));
        $this->assertEquals('one', $request->getHeader('1'));
        $this->assertEquals('a=b&baz=bam&t=1', $request->getQuery());
    }

    public function testClientMergesDefaultHeadersCaseInsensitively()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['defaults' => ['headers' => ['Foo' => 'Bar']]]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com?a=b', [
            'headers' => ['foo' => 'custom', 'user-agent' => 'test']
        ]);
        $this->assertEquals('test', $request->getHeader('User-Agent'));
        $this->assertEquals('custom', $request->getHeader('Foo'));
    }

    public function testUsesBaseUrlWhenNoUrlIsSet()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com/baz?bam=bar']);
        $this->assertEquals(
            'http://www.foo.com/baz?bam=bar',
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET')->getUrl()
        );
    }

    public function testUsesBaseUrlCombinedWithProvidedUrl()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com/baz?bam=bar']);
        $this->assertEquals(
            'http://www.foo.com/bar/bam',
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'bar/bam')->getUrl()
        );
    }

    public function testUsesBaseUrlCombinedWithProvidedUrlViaUriTemplate()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com/baz?bam=bar']);
        $this->assertEquals(
            'http://www.foo.com/bar/123',
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', ['bar/{bam}', ['bam' => '123']])->getUrl()
        );
    }

    public function testSettingAbsoluteUrlOverridesBaseUrl()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com/baz?bam=bar']);
        $this->assertEquals(
            'http://www.foo.com/foo',
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/foo')->getUrl()
        );
    }

    public function testSettingAbsoluteUriTemplateOverridesBaseUrl()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com/baz?bam=bar']);
        $this->assertEquals(
            'http://goo.com/1',
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest(
                'GET',
                ['http://goo.com/{bar}', ['bar' => '1']]
            )->getUrl()
        );
    }

    public function testCanSetRelativeUrlStartingWithHttp()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $this->assertEquals(
            'http://www.foo.com/httpfoo',
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'httpfoo')->getUrl()
        );
    }

    public function testClientSendsRequests()
    {
        $response = new Response(200);
        $adapter = new MockAdapter();
        $adapter->setResponse($response);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter]);
        $this->assertSame($response, $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://test.com'));
        $this->assertEquals('http://test.com', $response->getEffectiveUrl());
    }

    public function testSendingRequestCanBeIntercepted()
    {
        $response = new Response(200);
        $response2 = new Response(200);
        $adapter = new MockAdapter();
        $adapter->setResponse($response);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->on(
            'before',
            function (BeforeEvent $e) use ($response2) {
                $e->intercept($response2);
            }
        );
        $this->assertSame($response2, $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://test.com'));
        $this->assertEquals('http://test.com', $response2->getEffectiveUrl());
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException
     * @expectedExceptionMessage No response
     */
    public function testEnsuresResponseIsPresentAfterSending()
    {
        $adapter = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\MockAdapter')
            ->setMethods(['send'])
            ->getMock();
        $adapter->expects($this->once())
            ->method('send');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httpbin.org');
    }

    public function testClientHandlesErrorsDuringBeforeSend()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->on('before', function ($e) {
            throw new \Exception('foo');
        });
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->on('error', function ($e) {
            $e->intercept(new Response(200));
        });
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://test.com')->getStatusCode());
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException
     * @expectedExceptionMessage foo
     */
    public function testClientHandlesErrorsDuringBeforeSendAndThrowsIfUnhandled()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->on('before', function ($e) {
            throw new RequestException('foo', $e->getRequest());
        });
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httpbin.org');
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException
     * @expectedExceptionMessage foo
     */
    public function testClientWrapsExceptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->on('before', function ($e) {
            throw new \Exception('foo');
        });
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httpbin.org');
    }

    public function testCanSetDefaultValues()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['foo' => 'bar']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDefaultOption('headers/foo', 'bar');
        $this->assertNull($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('foo'));
        $this->assertEquals('bar', $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('headers/foo'));
    }

    public function testSendsAllInParallel()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock([
            new Response(200),
            new Response(201),
            new Response(202),
        ]));
        $history = new History();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($history);

        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://test.com'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('POST', 'http://test.com'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', 'http://test.com')
        ];

        $/* Replaced /* Replaced /* Replaced client */ */ */->sendAll($requests);
        $requests = array_map(function ($r) { return $r->getMethod(); }, $history->getRequests());
        $this->assertContains('GET', $requests);
        $this->assertContains('POST', $requests);
        $this->assertContains('PUT', $requests);
    }

    public function testCanSetCustomParallelAdapter()
    {
        $called = false;
        $pa = new FakeParallelAdapter(new MockAdapter(function () use (&$called) {
            $called = true;
            return new Response(203);
        }));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['parallel_adapter' => $pa]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->sendAll([$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://www.foo.com')]);
        $this->assertTrue($called);
    }

    public function testCanDisableAuthPerRequest()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['defaults' => ['auth' => 'foo']]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://test.com');
        $this->assertEquals('foo', $request->getConfig()['auth']);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://test.com', ['auth' => null]);
        $this->assertFalse($request->getConfig()->hasKey('auth'));
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     */
    public function testHasDeprecatedGetEmitter()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher();
    }

    public function testUsesProxyEnvironmentVariables()
    {
        $http = isset($_SERVER['HTTP_PROXY']) ? $_SERVER['HTTP_PROXY'] : null;
        $https = isset($_SERVER['HTTPS_PROXY']) ? $_SERVER['HTTPS_PROXY'] : null;
        unset($_SERVER['HTTP_PROXY']);
        unset($_SERVER['HTTPS_PROXY']);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $this->assertNull($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('proxy'));

        $_SERVER['HTTP_PROXY'] = '127.0.0.1';
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $this->assertEquals(
            ['http' => '127.0.0.1'],
            $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('proxy')
        );

        $_SERVER['HTTPS_PROXY'] = '127.0.0.2';
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $this->assertEquals(
            ['http' => '127.0.0.1', 'https' => '127.0.0.2'],
            $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('proxy')
        );

        $_SERVER['HTTP_PROXY'] = $http;
        $_SERVER['HTTPS_PROXY'] = $https;
    }
}
