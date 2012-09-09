<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Utils;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultCacheStorage;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultCacheKeyProvider;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultCanCacheStrategy;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultRevalidation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CallbackCanCacheStrategy;
use Doctrine\Common\Cache\ArrayCache;

/**
 * @group server
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin
 */
class CachePluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage A storage or adapter option is required
     */
    public function testConstructorEnsuresStorageOrAdapterIsSet()
    {
        $plugin = new CachePlugin(array());
    }

    public function testAddsDefaultCollaborators()
    {
        $this->assertNotEmpty(CachePlugin::getSubscribedEvents());
        $plugin = new CachePlugin(array(
            'storage' => $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheStorageInterface')->getMockForAbstractClass()
        ));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheStorageInterface', $this->readAttribute($plugin, 'storage'));
        $this->assertInstanceOf(
            '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheKeyProviderInterface',
            $this->readAttribute($plugin, 'keyProvider')
        );
        $this->assertInstanceOf(
            '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CanCacheStrategyInterface',
            $this->readAttribute($plugin, 'canCache')
        );
        $this->assertInstanceOf(
            '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\RevalidationInterface',
            $this->readAttribute($plugin, 'revalidation')
        );
    }

    public function testUsesCreatedCacheStorage()
    {
        $plugin = new CachePlugin(array(
            'adapter' => $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterInterface')->getMockForAbstractClass()
        ));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheStorageInterface', $this->readAttribute($plugin, 'storage'));
    }

    public function testUsesProvidedOptions()
    {
        $can = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CanCacheStrategyInterface')->getMockForAbstractClass();
        $revalidate = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\RevalidationInterface')->getMockForAbstractClass();
        $key = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheKeyProviderInterface')->getMockForAbstractClass();
        $plugin = new CachePlugin(array(
            'storage' => $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheStorageInterface')->getMockForAbstractClass(),
            'can_cache'    => $can,
            'revalidation' => $revalidate,
            'key_provider' => $key
        ));
        $this->assertSame($key, $this->readAttribute($plugin, 'keyProvider'));
        $this->assertSame($can, $this->readAttribute($plugin, 'canCache'));
        $this->assertSame($revalidate, $this->readAttribute($plugin, 'revalidation'));
    }

    public function satisfyProvider()
    {
        $req1 = new Request('GET', 'http://foo.com', array('Cache-Control' => 'no-cache'));
        $req2 = clone $req1;
        $req2->getParams()->set('cache.revalidate', 'skip');
        $req3 = clone $req1;
        $req3->getParams()->set('cache.revalidate', 'never');

        return array(
            // The response is too old to satisfy the request
            array(new Request('GET', 'http://foo.com', array('Cache-Control' => 'max-age=20')), new Response(200, array('Age' => 100)), false, false),
            // The response cannot satisfy the request because it is stale
            array(new Request('GET', 'http://foo.com'), new Response(200, array('Cache-Control' => 'max-age=10', 'Age' => 100)), false, false),
            // Allows the expired response to satisfy the request because of the max-stale
            array(new Request('GET', 'http://foo.com', array('Cache-Control' => 'max-stale=15')), new Response(200, array('Cache-Control' => 'max-age=90', 'Age' => 100)), true, false),
            // Max stale is > than the allowed staleness
            array(new Request('GET', 'http://foo.com', array('Cache-Control' => 'max-stale=5')), new Response(200, array('Cache-Control' => 'max-age=90', 'Age' => 100)), false, false),
            // Performs cache revalidation
            array($req1, new Response(200), true, true),
            // Does not perform revalidation
            array($req2, new Response(200), true, false),
            // Does not perform revalidation and fails
            array($req3, new Response(200), false, false),
        );
    }

    /**
     * @dataProvider satisfyProvider
     */
    public function testChecksIfResponseCanSatisfyRequest($request, $response, $can, $revalidates)
    {
        $didRevalidate = false;
        $revalidate = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\RevalidationInterface')
            ->setMethods(array('revalidate'))
            ->getMockForAbstractClass();

        $revalidate->expects($this->any())
            ->method('revalidate')
            ->will($this->returnCallback(function () use (&$didRevalidate) {
                $didRevalidate = true;
                return true;
            }));

        $plugin = new CachePlugin(array(
            'storage' => $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheStorageInterface')->getMockForAbstractClass(),
            'revalidation' => $revalidate
        ));

        $this->assertEquals($can, $plugin->canResponseSatisfyRequest($request, $response));
        $this->assertEquals($didRevalidate, $revalidates);
    }

    public function testDoesNothingWhenRequestIsNotCacheable()
    {
        $storage = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheStorageInterface')
            ->setMethods(array('fetch'))
            ->getMockForAbstractClass();
        $storage->expects($this->never())->method('fetch');

        $plugin = new CachePlugin(array(
            'storage'   => $storage,
            'can_cache' => new CallbackCanCacheStrategy(function () { return false; })
        ));

        $plugin->onRequestBeforeSend(new Event(array(
            'request' => new Request('GET', 'http://foo.com')
        )));
    }

    public function testInjectsSatisfiableResponses()
    {
        $storage = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CacheStorageInterface')
            ->setMethods(array('fetch'))
            ->getMockForAbstractClass();
        $storage->expects($this->once())->method('fetch')->will($this->returnValue(array(200, array(), 'foo')));
        $plugin = new CachePlugin(array('storage' => $storage));
        $request = new Request('GET', 'http://foo.com');
        $plugin->onRequestBeforeSend(new Event(array(
            'request' => $request
        )));
        $this->assertEquals(200, $request->getResponse()->getStatusCode());
        $this->assertEquals('foo', $request->getResponse()->getBody(true));
        $this->assertContains('key=', (string) $request->getResponse()->getHeader('X-/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Cache'));
        $this->assertTrue($request->getResponse()->hasHeader('Age'));
    }

    public function testCachesResponsesWhenCacheable()
    {
        $cache = new ArrayCache();
        $adapter = new DoctrineCacheAdapter($cache);
        $plugin = new CachePlugin(array('adapter' => $adapter));

        $request = new Request('GET', 'http://foo.com');
        $response = new Response(200, array(), 'Foo');
        $plugin->onRequestBeforeSend(new Event(array(
            'request' => $request
        )));
        $plugin->onRequestSent(new Event(array(
            'request'  => $request,
            'response' => $response
        )));

        $data = $this->readAttribute($cache, 'data');
        $this->assertNotEmpty($data);
        $data = end($data);
        $this->assertEquals(200, $data[0]);
        $this->assertInternalType('array', $data[1]);
        $this->assertEquals('Foo', $data[2]);
    }
}
