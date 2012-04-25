<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\CurlException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti;

/**
 * @group server
 */
class ExponentialBackoffPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function delayClosure($retries)
    {
        return 0;
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::getFailureCodes
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::getMaxRetries
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::setMaxRetries
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::setFailureCodes
     */
    public function testConstructsCorrectly()
    {
        $plugin = new ExponentialBackoffPlugin(2, array(500, 503, 502), array($this, 'delayClosure'));
        $this->assertEquals(2, $plugin->getMaxRetries());
        $this->assertEquals(array(500, 503, 502), $plugin->getFailureCodes());

        // You can specify any codes you want... Probably not a good idea though
        $plugin->setFailureCodes(array(200, 204));
        $this->assertEquals(array(200, 204), $plugin->getFailureCodes());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::calculateWait
     */
    public function testCalculateWait()
    {
        $plugin = new ExponentialBackoffPlugin(2);
        $this->assertEquals(1, $plugin->calculateWait(0));
        $this->assertEquals(2, $plugin->calculateWait(1));
        $this->assertEquals(4, $plugin->calculateWait(2));
        $this->assertEquals(8, $plugin->calculateWait(3));
        $this->assertEquals(16, $plugin->calculateWait(4));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin
     */
    public function testRetriesRequests()
    {
        // Create a script to return several 500 and 503 response codes
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 500 Internal Server Error\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 500 Internal Server Error\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ndata"
        ));

        $plugin = new ExponentialBackoffPlugin(2, null, array($this, 'delayClosure'));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        // Make sure it eventually completed successfully
        $this->assertEquals(200, $request->getResponse()->getStatusCode());
        $this->assertEquals('OK', $request->getResponse()->getReasonPhrase());
        $this->assertEquals('data', $request->getResponse()->getBody(true));

        // Check that three requests were made to retry this request
        $this->assertEquals(3, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin
     */
    public function testRetriesRequestsUsingReasonPhraseMatch()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 400 FooError\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 400 FooError\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ndata"
        ));

        $plugin = new ExponentialBackoffPlugin(2, array('FooError'), array($this, 'delayClosure'));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        // Make sure it eventually completed successfully
        $this->assertEquals(200, $request->getResponse()->getStatusCode());
        $this->assertEquals('data', $request->getResponse()->getBody(true));

        // Check that three requests were made to retry this request
        $this->assertEquals(3, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::onRequestSent
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::onRequestPoll
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException
     */
    public function testAllowsFailureOnMaxRetries()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 500 Internal Server Error\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 500 Internal Server Error\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 500 Internal Server Error\r\nContent-Length: 0\r\n\r\n"
        ));

        $plugin = new ExponentialBackoffPlugin(2, null, array($this, 'delayClosure'));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        // This will fail because the plugin isn't retrying the request because
        // the max number of retries is exceeded (1 > 0)
        $request->send();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::onRequestSent
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::onRequestPoll
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     */
    public function testRetriesPooledRequestsUsingDelayAndPollingEvent()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 500 Internal Server Error\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ndata"
        ));

        // Need to sleep for one second to make sure that the polling works
        // correctly in the observer
        $plugin = new ExponentialBackoffPlugin(1, null, function($r) {
            return 1;
        });

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        // Make sure it eventually completed successfully
        $this->assertEquals('data', $request->getResponse()->getBody(true));

        // Check that two requests were made to retry this request
        $this->assertEquals(2, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::getDefaultFailureCodes
     */
    public function testReturnsDefaultFailureCodes()
    {
        $this->assertNotEmpty(ExponentialBackoffPlugin::getDefaultFailureCodes());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::getDefaultFailureCodes
     */
    public function testUsesDefaultFailureCodesByDefault()
    {
        $p = new ExponentialBackoffPlugin();
        $this->assertEquals($p->getFailureCodes(), ExponentialBackoffPlugin::getDefaultFailureCodes());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::onRequestSent
     */
    public function testAllowsCallableFailureCodes()
    {
        $a = 0;
        $plugin = new ExponentialBackoffPlugin(1, function($request, $response) use (&$a) {
            // Look for a Foo header
            if ($response->hasHeader('Foo')) {
                $a = 1;
                return true;
            }
        }, array($this, 'delayClosure'));

        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\nFoo: Bar\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ndata"
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        // Make sure it eventually completed successfully
        $this->assertEquals('data', $request->getResponse()->getBody(true));
        // Check that the callback saw the request and header
        $this->assertEquals(1, $a);
        // Check that two requests were made to retry this request
        $this->assertEquals(2, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::onRequestSent
     */
    public function testExponentiallyBacksOffCurlErrors()
    {
        $plugin = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin', array('retryRequest'));

        // Mock the retryRequest method so that it does nothing, but ensure
        // that it is called exactly once
        $plugin->expects($this->once())
            ->method('retryRequest')
            ->will($this->returnValue(null));

        // Create an exception that is found in the default curl exception list
        $exception = new CurlException('Curl');
        $exception->setError('foo', CURLE_OPERATION_TIMEOUTED);

        // Create a dummy event to send to the plugin
        $event = new Event(array(
            'request' => new Request('GET', 'http://test.com'),
            'response' => null,
            'exception' => $exception
        ));
        // Ensure the it uses the name we're looking for
        $event->setName('request.exception');

        // Trigger the event
        $plugin->onRequestSent($event);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin::onRequestSent
     */
    public function testAllowsCustomFailureMethodsToPuntToDefaultMethod()
    {
        $count = 0;

        $plugin = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin')
            ->setMethods(array('retryRequest'))
            ->setConstructorArgs(array(2, function() use (&$count) {
                $count++;
            }, array($this, 'delayClosure')))
            ->getMock();

        $plugin->expects($this->once())
            ->method('retryRequest')
            ->will($this->returnValue(null));

        $event = new Event(array(
            'request' => new Request('GET', 'http://test.com'),
            'response' => new Response(500)
        ));
        $event->setName('request.exception');

        $plugin->onRequestSent($event);
        $this->assertEquals(1, $count);
    }
}
