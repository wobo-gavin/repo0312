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
use Doctrine\Common\Cache\ArrayCache;

/**
 * @group server
 */
class CachePluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var ArrayCache
     */
    private $cache;

    /**
     * @var DoctrineCacheAdapter
     */
    private $adapter;

    /**
     * Remove node.js generated Connection: keep-alive header
     *
     * @param string $response Response
     *
     * @return string
     */
    protected function removeKeepAlive($response)
    {
        return str_replace("Connection: keep-alive\r\n", '', $response);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->cache = new ArrayCache();
        $this->adapter = new DoctrineCacheAdapter($this->cache);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::__construct
     */
    public function testConstructorSetsValues()
    {
        $plugin = new CachePlugin($this->adapter, 1200);

        $this->assertEquals($this->adapter, $this->readAttribute($plugin, 'adapter'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::onRequestSent
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::onRequestBeforeSend
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::saveCache
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::getCacheKey
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::canResponseSatisfyRequest
     */
    public function testSavesResponsesInCache()
    {
        // Send a 200 OK script to the testing server
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ndata",
            "HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ntest"
        ));

        // Create a new Cache plugin
        $plugin = new CachePlugin($this->adapter);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->setCurlMulti(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        // Calculate the cache key like the cache plugin does
        $key = $plugin->getCacheKey($request);
        // Make sure that the cache plugin set the request in cache
        $this->assertNotNull($this->adapter->fetch($key));

        // Clear out the requests stored on the server to make sure we didn't send a new request
        $this->getServer()->flush();

        // Test that the request is set manually
        // The test server has no more script data, so if it actually sends a
        // request it will fail the test.
        $this->assertEquals($key, $plugin->getCacheKey($request));
        $request->setState('new');
        $request->send();
        $this->assertEquals('data', $request->getResponse()->getBody(true));

        // Make sure a request wasn't sent
        $this->assertEquals(0, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::onRequestSent
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::onRequestBeforeSend
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::saveCache
     */
    public function testSkipsNonReadableResponseBodies()
    {
        // Send a 200 OK script to the testing server
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ndata");

        // Create a new Cache plugin
        $plugin = new CachePlugin($this->adapter);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        // Create a new request using the Cache plugin
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();

        // Create a temp file that is not readable
        $tempFile = tempnam('/tmp', 'temp_stream_data');
        // Set the non-readable stream as the response body so that it can't be cached
        $request->setResponseBody(EntityBody::factory(
            fopen($tempFile, 'w')
        ));

        $request->send();

        // Calculate the cache key like the cache plugin does
        $key = $plugin->getCacheKey($request);
        // Make sure that the cache plugin set the request in cache
        $this->assertFalse($this->adapter->fetch($key));
    }

    public function cacheKeyDataProvider()
    {
        $r = array(
            array('', 'gz_get_http_www.test.com/path?q=abc_host=www.test.com&date=123', 'http://www.test.com/path?q=abc', "Host: www.test.com\r\nDate: 123"),
            array('query = q', 'gz_get_http_www.test.com/path_host=www.test.com&date=123', 'http://www.test.com/path?q=abc', "Host: www.test.com\r\nDate: 123"),
            array('query=q; header=Date;', 'gz_get_http_www.test.com/path_host=www.test.com', 'http://www.test.com/path?q=abc', "Host: www.test.com\r\nDate: 123"),
            array('query=a,  q; header=Date, Host;', 'gz_get_http_www.test.com/path_', 'http://www.test.com/path?q=abc&a=123', "Host: www.test.com\r\nDate: 123"),
        );

        return $r;
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::getCacheKey
     * @dataProvider cacheKeyDataProvider
     */
    public function testCreatesCacheKeysUsingFilters($filter, $key, $url, $headers = null)
    {
        // Create a new Cache plugin
        $plugin = new CachePlugin($this->adapter);

        // Generate the header array
        $h = null;
        if ($headers) {
            $h = array();
            foreach (explode("\r\n", $headers) as $header) {
                list($k, $v) = explode(': ', $header);
                $h[$k] = $v;
            }
        }

        // Create the request
        $request = RequestFactory::getInstance()->create('GET', $url, $h);
        $request->getParams()->set('cache.key_filter', $filter);
        $request->removeHeader('User-Agent');

        $this->assertEquals($key, $plugin->getCacheKey($request, true));

        // Make sure that the encoded request is returned when $raw is false
        $this->assertNotEquals($key, $plugin->getCacheKey($request));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::getCacheKey
     */
    public function testCreatesEncodedKeys()
    {
        $plugin = new CachePlugin($this->adapter);
        $request = RequestFactory::getInstance()->fromMessage(
            "GET / HTTP/1.1\r\nHost: www.test.com\r\nCache-Control: no-cache, no-store, max-age=120"
        );

        $key = $plugin->getCacheKey($request);

        $this->assertEquals(1, preg_match('/^gz_[a-z0-9]{32}$/', $key));

        // Make sure that the same value is returned in a subsequent call
        $this->assertEquals($key, $plugin->getCacheKey($request));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::onRequestSent
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::onRequestBeforeSend
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::saveCache
     */
    public function testRequestsCanOverrideTtlUsingCacheParam()
    {
        $plugin = new CachePlugin($this->adapter);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.test.com/');
        $request->getParams()->set('cache.override_ttl', 1000);
        $request->setResponse(Response::fromMessage("HTTP/1.1 200 OK\r\nCache-Control: max-age=100\r\nContent-Length: 4\r\n\r\nData"), true);
        $request->send();

        $request2 = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.test.com/');
        $response = $request2->send();

        $token = $response->getTokenizedHeader('X-/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Cache', ', ');
        $this->assertEquals(1000, $token['ttl']);
        $this->assertEquals(true, $token->hasKey('key'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::canResponseSatisfyRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::onRequestSent
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::onRequestBeforeSend
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::saveCache
     */
    public function testRequestsCanAcceptStaleResponses()
    {
        $server = $this->getServer();

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $plugin = new CachePlugin($this->adapter);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('test');
        // Cache this response for 1000 seconds if it is cacheable
        $request->getParams()->set('cache.override_ttl', 1000);
        $request->setResponse(Response::fromMessage("HTTP/1.1 200 OK\r\nExpires: " . Utils::getHttpDate('-1 second') . "\r\nContent-Length: 4\r\n\r\nData"), true);
        $request->send();

        sleep(1);

        // Accept responses that are up to 100 seconds expired
        $request2 = $/* Replaced /* Replaced /* Replaced client */ */ */->get('test');
        $request2->addCacheControlDirective('max-stale', 100);
        $response = $request2->send();
        $token = $response->getTokenizedHeader('X-/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Cache', ', ');
        $this->assertEquals(1000, $token['ttl']);

        // Accepts any stale response
        $request3 = $/* Replaced /* Replaced /* Replaced client */ */ */->get('test');
        $request3->addCacheControlDirective('max-stale');
        $response = $request3->send();
        $token = $response->getTokenizedHeader('X-/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Cache', ', ');
        $this->assertEquals(1000, $token['ttl']);

        // Will not accept the stale cached entry
        $server->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\nData");
        $request4 = $/* Replaced /* Replaced /* Replaced client */ */ */->get('test');
        $request4->addCacheControlDirective('max-stale', 0);
        $response = $request4->send();
        $this->assertEquals("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\nData", $this->removeKeepAlive((string) $response));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::canResponseSatisfyRequest
     */
    public function testChecksIfResponseCanSatisfyRequest()
    {
        $plugin = new CachePlugin($this->adapter);

        // Send some responses to the test server for cache validation
        $server = $this->getServer();

        // No restrictions
        $request = RequestFactory::getInstance()->create('GET', $server->getUrl());
        $response = new Response(200, array('Date' => Utils::getHttpDate('now')));
        $this->assertTrue($plugin->canResponseSatisfyRequest($request, $response));

        // Request max-age is less than response age
        $request = RequestFactory::getInstance()->create('GET', $server->getUrl());
        $request->addCacheControlDirective('max-age', 100);
        $response = new Response(200, array('Age' => 10));
        $this->assertTrue($plugin->canResponseSatisfyRequest($request, $response));

        // Request must have something fresher than 200 seconds
        $response->setHeader('Date', Utils::getHttpDate('-200 days'));
        $response->removeHeader('Age');
        $request->setHeader('Cache-Control', 'max-age=200');
        $this->assertFalse($plugin->canResponseSatisfyRequest($request, $response));

        // Response says it's too old
        $request->removeHeader('Cache-Control');
        $response->setHeader('Cache-Control', 'max-age=86400');
        $this->assertFalse($plugin->canResponseSatisfyRequest($request, $response));

        // Response is OK
        $response->setHeader('Date', Utils::getHttpDate('-1 hour'));
        $this->assertTrue($plugin->canResponseSatisfyRequest($request, $response));
    }

    /**
     * Data provider to test cache revalidation
     *
     * @return array
     */
    public function cacheRevalidationDataProvider()
    {
        return array(
            // Forces revalidation that passes
            array(
                true,
                "Pragma: no-cache\r\n\r\n",
                "HTTP/1.1 200 OK\r\nDate: " . Utils::getHttpDate('-100 hours') . "\r\nContent-Length: 4\r\n\r\nData",
                "HTTP/1.1 304 NOT MODIFIED\r\nCache-Control: max-age=2000000\r\nContent-Length: 0\r\n\r\n",
            ),
            // Forces revalidation that overwrites what is in cache
            array(
                false,
                "\r\n\r\n",
                "HTTP/1.1 200 OK\r\nCache-Control: must-revalidate, no-cache\r\nDate: " . Utils::getHttpDate('-10 hours') . "\r\nContent-Length: 4\r\n\r\nData",
                "HTTP/1.1 200 OK\r\nContent-Length: 5\r\n\r\nDatas",
                "HTTP/1.1 200 OK\r\nContent-Length: 5\r\nDate: " . Utils::getHttpDate('now') . "\r\n\r\nDatas"
            ),
            // Must get a fresh copy because the request is declining revalidation
            array(
                false,
                "\r\n\r\n",
                "HTTP/1.1 200 OK\r\nCache-Control: no-cache\r\nDate: " . Utils::getHttpDate('-3 hours') . "\r\nContent-Length: 4\r\n\r\nData",
                null,
                null,
                'never'
            ),
            // Skips revalidation because the request is accepting the cached copy
            array(
                true,
                "\r\n\r\n",
                "HTTP/1.1 200 OK\r\nCache-Control: no-cache\r\nDate: " . Utils::getHttpDate('-3 hours') . "\r\nContent-Length: 4\r\n\r\nData",
                null,
                null,
                'skip'
            ),
            // Throws an exception during revalidation
            array(
                false,
                "\r\n\r\n",
                "HTTP/1.1 200 OK\r\nCache-Control: no-cache\r\nDate: " . Utils::getHttpDate('-3 hours') . "\r\n\r\nData",
                "HTTP/1.1 500 INTERNAL SERVER ERROR\r\nContent-Length: 0\r\n\r\n"
            ),
            // ETag mismatch
            array(
                false,
                "\r\n\r\n",
                "HTTP/1.1 200 OK\r\nCache-Control: no-cache\r\nETag: \"123\"\r\nDate: " . Utils::getHttpDate('-10 hours') . "\r\n\r\nData",
                "HTTP/1.1 304 NOT MODIFIED\r\nETag: \"123456\"\r\n\r\n",
            ),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::canResponseSatisfyRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::revalidate
     * @dataProvider cacheRevalidationDataProvider
     */
    public function testRevalidatesResponsesAgainstOriginServer($can, $request, $response, $validate = null, $result = null, $param = null)
    {
        // Send some responses to the test server for cache validation
        $server = $this->getServer();
        $server->flush();

        if ($validate) {
            $server->enqueue($validate);
        }

        $request = RequestFactory::getInstance()->fromMessage("GET / HTTP/1.1\r\nHost: 127.0.0.1:" . $server->getPort() . "\r\n" . $request);
        $response = Response::fromMessage($response);
        $request->setClient(new Client());

        if ($param) {
            $request->getParams()->set('cache.revalidate', $param);
        }

        $plugin = new CachePlugin($this->adapter);
        $this->assertEquals($can, $plugin->canResponseSatisfyRequest($request, $response), '-> ' . $request . "\n" . $response);

        if ($result) {
            // Get rid of dates
            $this->assertEquals(
                preg_replace('/(Date:\s)(.*)(\r\n)/', '$1$3', (string) $result),
                preg_replace('/(Date:\s)(.*)(\r\n)/', '$1$3', (string) $request->getResponse())
            );
        }

        if ($validate) {
            $this->assertEquals(1, count($server->getReceivedRequests()));
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin
     */
    public function testCachesResponsesAndHijacksRequestsWhenApplicable()
    {
        $server = $this->getServer();
        $server->flush();
        $server->enqueue("HTTP/1.1 200 OK\r\nCache-Control: max-age=1000\r\nContent-Length: 4\r\n\r\nData");

        $plugin = new CachePlugin($this->adapter);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($server->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->getCurlOptions()->set(CURLOPT_TIMEOUT, 2);
        $request2 = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request2->getCurlOptions()->set(CURLOPT_TIMEOUT, 2);
        $request->send();
        $request2->send();

        $this->assertEquals(1, count($server->getReceivedRequests()));
        $this->assertEquals(true, $request2->getResponse()->hasHeader('X-/* Replaced /* Replaced /* Replaced Guzzle */ */ */-Cache'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin::revalidate
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException
     */
    public function testRemovesMissingEntitesFromCacheWhenRevalidating()
    {
        $server = $this->getServer();
        $server->enqueue(array(
            "HTTP/1.1 200 OK\r\nCache-Control: max-age=1000, no-cache\r\nContent-Length: 4\r\n\r\nData",
            "HTTP/1.1 404 NOT FOUND\r\nContent-Length: 0\r\n\r\n"
        ));

        $plugin = new CachePlugin($this->adapter);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($server->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        $request1 = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $request1->send();
        $this->assertTrue($this->cache->contains($plugin->getCacheKey($request1)));
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/')->send();
    }

    public function testOnlyCachesCacheableRequests()
    {
        $plugin = new CachePlugin($this->adapter);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $plugin->onRequestBeforeSend(new Event(array('request' => $/* Replaced /* Replaced /* Replaced client */ */ */->post('/'))));
        $this->assertEquals(0, count($this->readAttribute($plugin, 'cached')));
    }

    public function testAllowsCustomCacheFilterStrategies()
    {
        $plugin = new CachePlugin($this->adapter);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl(), array(
            'params.cache.filter_strategy' => function ($request) {
                return true;
            }
        ));
        $plugin->onRequestBeforeSend(new Event(array('request' => $/* Replaced /* Replaced /* Replaced client */ */ */->post('/'))));
        $this->assertEquals(1, count($this->readAttribute($plugin, 'cached')));

        $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->set('params.cache.filter_strategy', function ($request) {
            return false;
        });
        $plugin->onRequestBeforeSend(new Event(array('request' => $/* Replaced /* Replaced /* Replaced client */ */ */->get('/'))));
        $this->assertEquals(1, count($this->readAttribute($plugin, 'cached')));
    }
}
