<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\ClientEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestBeforeSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client
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
        $this->assertEquals(null, (new Client())->getBaseUrl());
        $this->assertEquals('http://foo', (new Client([
            'base_url' => 'http://foo'
        ]))->getBaseUrl());
    }

    public function testCanSpecifyBaseUrlUriTemplate()
    {
        $this->assertEquals('http://foo.com/baz/', (new Client([
            'base_url' => ['http://foo.com/{var}/', ['var' => 'baz']]
        ]))->getBaseUrl());
    }

    public function testClientUsesDefaultAdapterWhenNoneIsSet()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        if (!extension_loaded('curl')) {
            $adapter = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\StreamAdapter';
        } elseif (ini_get('allow_url_fopen')) {
            $adapter = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\StreamingProxyAdapter';
        } else {
            $adapter = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Curl\CurlAdapter';
        }
        $this->assertInstanceOf($adapter, $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'adapter'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Foo
     */
    public function testCanSpecifyAdapter()
    {
        $adapter = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\AdapterInterface')
            ->setMethods('send')
            ->getMockForAbstractClass();
        $adapter->expects($this->once())
            ->method('send')
            ->will($this->throwException(new \Exception('Foo')));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Foo
     */
    public function testCanSpecifyMessageFactory()
    {
        $factory = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactoryInterface')
            ->setMethods('createRequest')
            ->getMockForAbstractClass();
        $factory->expects($this->once())
            ->method('createRequest')
            ->will($this->throwException(new \Exception('Foo')));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['message_factory' => $factory]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get();
    }

    public function testAddsDefaultUserAgentHeaderWithDefaultOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['defaults' => ['allow_redirects' => false]]);
        $this->assertFalse($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('allow_redirects'));
        $this->assertEquals(['User-Agent' => Client::getDefaultUserAgent()], $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('headers'));
    }

    public function testAddsDefaultUserAgentHeaderWithoutDefaultOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $this->assertEquals(['User-Agent' => Client::getDefaultUserAgent()], $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('headers'));
    }

    public function testProvidesConfigPathValues()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['foo' => ['baz' => 'bar']]);
        $this->assertEquals('bar', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('foo/baz'));
    }

    private function getRequestClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client')
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
            $request = $/* Replaced /* Replaced /* Replaced client */ */ */->{$method}('http://foo.com', ['X-Baz' => 'Bar'], $body, ['query' => ['a' => 'b']]);
        } else {
            $request = $/* Replaced /* Replaced /* Replaced client */ */ */->{$method}('http://foo.com', ['X-Baz' => 'Bar'], ['query' => ['a' => 'b']]);
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
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'defaults' => [
                'headers' => ['Foo' => 'Bar'],
                'query' => ['baz' => 'bam'],
                'exceptions' => false
            ]
        ]);

        $e = null;
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addListener(ClientEvents::CREATE_REQUEST, function ($ev) use (&$e) {
            $e = $ev;
        });

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://foo.com?a=b', ['Hi' => 'there'], null, [
            'allow_redirects' => false,
            'query' => ['t' => 1],
            'headers' => ['1' => 'one']
        ]);

        $this->assertNotNull($e);
        $o = $e->getRequestOptions();
        $this->assertFalse($o['allow_redirects']);
        $this->assertFalse($o['exceptions']);
        $this->assertEquals('Bar', $request->getHeader('Foo'));
        $this->assertEquals('there', $request->getHeader('Hi'));
        $this->assertEquals('one', $request->getHeader('1'));
        $this->assertEquals('a=b&baz=bam&t=1', $request->getQuery());

        // Ensure the request uses a clone of the /* Replaced /* Replaced /* Replaced client */ */ */ event dispatcher
        $this->assertNotEmpty(
            $request->getEventDispatcher()->getListeners(ClientEvents::CREATE_REQUEST)
        );
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
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addListener(
            RequestEvents::BEFORE_SEND,
            function (RequestBeforeSendEvent $e) use ($response2) {
                $e->intercept($response2);
            }
        );
        $this->assertSame($response2, $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://test.com'));
        $this->assertEquals('http://test.com', $response2->getEffectiveUrl());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No response
     */
    public function testEnsuresResponseIsPresentAfterSending()
    {
        $adapter = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\MockAdapter')
            ->setMethods(['send'])
            ->getMock();
        $adapter->expects($this->once())
            ->method('send');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
    }

    public function testClientHandlesErrorsDuringBeforeSend()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addListener(RequestEvents::BEFORE_SEND, function ($e) {
            throw new RequestException('foo', $e->getRequest());
        });
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addListener(RequestEvents::ERROR, function ($e) {
            $e->intercept(new Response(200));
        });
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get('/')->getStatusCode());
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException
     * @expectedExceptionMessage foo
     */
    public function testClientHandlesErrorsDuringBeforeSendAndThrowsIfUnhandled()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addListener(RequestEvents::BEFORE_SEND, function ($e) {
            throw new RequestException('foo', $e->getRequest());
        });
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
    }
}
