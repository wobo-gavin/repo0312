<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\CurlException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CachePlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter;
use Doctrine\Common\Cache\ArrayCache;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultCacheStorage;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultRevalidation
 * @group server
 */
class DefaultRevalidationTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected function getHttpDate($time)
    {
        return gmdate(ClientInterface::HTTP_DATE, strtotime($time));
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
                "HTTP/1.1 200 OK\r\nDate: " . $this->getHttpDate('-100 hours') . "\r\nContent-Length: 4\r\n\r\nData",
                "HTTP/1.1 304 NOT MODIFIED\r\nCache-Control: max-age=2000000\r\nContent-Length: 0\r\n\r\n",
            ),
            // Forces revalidation that overwrites what is in cache
            array(
                false,
                "\r\n",
                "HTTP/1.1 200 OK\r\nCache-Control: must-revalidate, no-cache\r\nDate: " . $this->getHttpDate('-10 hours') . "\r\nContent-Length: 4\r\n\r\nData",
                "HTTP/1.1 200 OK\r\nContent-Length: 5\r\n\r\nDatas",
                "HTTP/1.1 200 OK\r\nContent-Length: 5\r\nDate: " . $this->getHttpDate('now') . "\r\n\r\nDatas"
            ),
            // Throws an exception during revalidation
            array(
                false,
                "\r\n",
                "HTTP/1.1 200 OK\r\nCache-Control: no-cache\r\nDate: " . $this->getHttpDate('-3 hours') . "\r\n\r\nData",
                "HTTP/1.1 500 INTERNAL SERVER ERROR\r\nContent-Length: 0\r\n\r\n"
            ),
            // ETag mismatch
            array(
                false,
                "\r\n",
                "HTTP/1.1 200 OK\r\nCache-Control: no-cache\r\nETag: \"123\"\r\nDate: " . $this->getHttpDate('-10 hours') . "\r\n\r\nData",
                "HTTP/1.1 304 NOT MODIFIED\r\nETag: \"123456\"\r\n\r\n",
            ),
        );
    }

    /**
     * @dataProvider cacheRevalidationDataProvider
     */
    public function testRevalidatesResponsesAgainstOriginServer($can, $request, $response, $validate = null, $result = null)
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

        $plugin = new CachePlugin(new DoctrineCacheAdapter(new ArrayCache()));
        $this->assertEquals(
            $can,
            $plugin->canResponseSatisfyRequest($request, $response),
            '-> ' . $request . "\n" . $response
        );

        if ($result) {
            $result = Response::fromMessage($result);
            $result->removeHeader('Date');
            $request->getResponse()->removeHeader('Date');
            $request->getResponse()->removeHeader('Connection');
            // Get rid of dates
            $this->assertEquals((string) $result, (string) $request->getResponse());
        }

        if ($validate) {
            $this->assertEquals(1, count($server->getReceivedRequests()));
        }
    }

    public function testHandles404RevalidationResponses()
    {
        $request = new Request('GET', 'http://foo.com');
        $request->setClient(new Client());
        $badResponse = new Response(404, array(), 'Oh no!');
        $badRequest = clone $request;
        $badRequest->setResponse($badResponse, true);
        $response = new Response(200, array(), 'foo');

        // Seed the cache
        $s = new DefaultCacheStorage(new DoctrineCacheAdapter(new ArrayCache()));
        $s->cache($request, $response);
        $this->assertNotNull($s->fetch($request));

        $rev = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultRevalidation')
            ->setConstructorArgs(array($s))
            ->setMethods(array('createRevalidationRequest'))
            ->getMock();

        $rev->expects($this->once())
            ->method('createRevalidationRequest')
            ->will($this->returnValue($badRequest));

        try {
            $rev->revalidate($request, $response);
            $this->fail('Should have thrown an exception');
        } catch (BadResponseException $e) {
            $this->assertSame($badResponse, $e->getResponse());
            $this->assertNull($s->fetch($request));
        }
    }

    public function testCanRevalidateWithPlugin()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\n" .
            "Date: Mon, 12 Nov 2012 03:06:37 GMT\r\n" .
            "Cache-Control: private, s-maxage=0, max-age=0, must-revalidate\r\n" .
            "Last-Modified: Mon, 12 Nov 2012 02:53:38 GMT\r\n" .
            "Content-Length: 2\r\n\r\nhi",
            "HTTP/1.0 304 Not Modified\r\n" .
            "Date: Mon, 12 Nov 2012 03:06:38 GMT\r\n" .
            "Content-Type: text/html; charset=UTF-8\r\n" .
            "Last-Modified: Mon, 12 Nov 2012 02:53:38 GMT\r\n" .
            "Age: 6302\r\n\r\n",
            "HTTP/1.0 304 Not Modified\r\n" .
            "Date: Mon, 12 Nov 2012 03:06:38 GMT\r\n" .
            "Content-Type: text/html; charset=UTF-8\r\n" .
            "Last-Modified: Mon, 12 Nov 2012 02:53:38 GMT\r\n" .
            "Age: 6302\r\n\r\n",
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new CachePlugin());
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send()->getStatusCode());
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send()->getStatusCode());
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send()->getStatusCode());
        $this->assertEquals(3, count($this->getServer()->getReceivedRequests()));
    }

    public function testCanHandleRevalidationFailures()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $lm = gmdate('c', time() - 60);
        $mock = new MockPlugin(array(
            new Response(200, array(
                'Date'           => $lm,
                'Cache-Control'  => 'max-age=100, must-revalidate, stale-if-error=9999',
                'Last-Modified'  => $lm,
                'Content-Length' => 2
            ), 'hi'),
            new CurlException('Bleh'),
            new CurlException('Bleh')
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new CachePlugin());
        $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send();
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('hi', $response->getBody(true));
        $this->assertEquals(3, count($mock->getReceivedRequests()));
        $this->assertEquals(0, count($mock->getQueue()));
    }

    public function testCanHandleStaleIfErrorWhenRevalidating()
    {
        $lm = gmdate('c', time() - 60);
        $mock = new MockPlugin(array(
            new Response(200, array(
                'Date' => $lm,
                'Cache-Control' => 'must-revalidate, max-age=0, stale-if-error=1200',
                'Last-Modified' => $lm,
                'Content-Length' => 2
            ), 'hi'),
            new CurlException('Oh no!'),
            new CurlException('Oh no!')
        ));
        $cache = new CachePlugin();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.example.com');
        $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($cache);
        $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber($mock);
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send()->getStatusCode());
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(0, $mock);
        $this->assertEquals('HIT from /* Replaced /* Replaced /* Replaced Guzzle */ */ */Cache', (string) $response->getHeader('X-Cache-Lookup'));
        $this->assertEquals('HIT_ERROR from /* Replaced /* Replaced /* Replaced Guzzle */ */ */Cache', (string) $response->getHeader('X-Cache'));
    }

    /**
     * @group issue-437
     */
    public function testDoesNotTouchClosureListeners()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\n" .
            "Date: Mon, 12 Nov 2012 03:06:37 GMT\r\n" .
            "Cache-Control: private, s-maxage=0, max-age=0, must-revalidate\r\n" .
            "Last-Modified: Mon, 12 Nov 2012 02:53:38 GMT\r\n" .
            "Content-Length: 2\r\n\r\nhi",
            "HTTP/1.0 304 Not Modified\r\n" .
            "Date: Mon, 12 Nov 2012 03:06:38 GMT\r\n" .
            "Content-Type: text/html; charset=UTF-8\r\n" .
            "Last-Modified: Mon, 12 Nov 2012 02:53:38 GMT\r\n" .
            "Age: 6302\r\n\r\n",
            "HTTP/1.0 304 Not Modified\r\n" .
            "Date: Mon, 12 Nov 2012 03:06:38 GMT\r\n" .
            "Content-Type: text/html; charset=UTF-8\r\n" .
            "Last-Modified: Mon, 12 Nov 2012 02:53:38 GMT\r\n" .
            "Age: 6302\r\n\r\n",
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->addSubscriber(new CachePlugin());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addListener('command.after_send', function(){});
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send()->getStatusCode());
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send()->getStatusCode());
        $this->assertEquals(200, $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send()->getStatusCode());
    }

}
