<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\ExceptionCollection;;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\CurlException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock\MockMulti;

/**
 * @group server
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
 */
class ExceptionCollectionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     */
    private $multi;

    /**
     * @var /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection
     */
    private $updates;

    private $mock;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->updates = new Collection();
        $this->multi = new MockMulti();
        $this->mock = $this->getWildcardObserver($this->multi);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::getInstance
     */
    public function testReturnsCachedInstance()
    {
        $c = CurlMulti::getInstance();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Curl\\CurlMultiInterface', $c);
        $this->assertSame($c, CurlMulti::getInstance());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::__destruct
     */
    public function testConstructorCreateMultiHandle()
    {
        $this->assertInternalType('resource', $this->multi->getHandle());
        $this->assertEquals('curl_multi', get_resource_type($this->multi->getHandle()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::__destruct
     */
    public function testDestructorClosesMultiHandle()
    {
        $handle = $this->multi->getHandle();
        $this->multi->__destruct();
        $this->assertFalse(is_resource($handle));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\curlMulti::add
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\curlMulti::all
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\curlMulti::count
     */
    public function testRequestsCanBeAddedAndCounted()
    {
        $multi = new CurlMulti();
        $mock = $this->getWildcardObserver($multi);
        $request1 = new Request('GET', 'http://www.google.com/');
        $multi->add($request1);
        $this->assertEquals(array($request1), $multi->all());

        $request2 = new Request('POST', 'http://www.google.com/');
        $multi->add($request2);
        $this->assertEquals(array($request1, $request2), $multi->all());
        $this->assertEquals(2, count($multi));

        $this->assertTrue($mock->has(CurlMulti::ADD_REQUEST));
        $this->assertFalse($mock->has(CurlMulti::REMOVE_REQUEST));
        $this->assertFalse($mock->has(CurlMulti::POLLING));
        $this->assertFalse($mock->has(CurlMulti::COMPLETE));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::remove
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::all
     */
    public function testRequestsCanBeRemoved()
    {
        $request1 = new Request('GET', 'http://www.google.com/');
        $this->multi->add($request1);
        $request2 = new Request('PUT', 'http://www.google.com/');
        $this->multi->add($request2);
        $this->assertEquals(array($request1, $request2), $this->multi->all());
        $this->assertSame($this->multi, $this->multi->remove($request1));
        $this->assertEquals(array($request2), $this->multi->all());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::reset
     */
    public function testsResetRemovesRequestsAndResetsState()
    {
        $request1 = new Request('GET', 'http://www.google.com/');
        $this->multi->add($request1);
        $this->multi->reset();
        $this->assertEquals(array(), $this->multi->all());
        $this->assertEquals('idle', $this->multi->getState());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::send
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::getState
     */
    public function testSendsRequestsInParallel()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\nBody");
        $this->assertEquals('idle', $this->multi->getState());
        $request = new Request('GET', $this->getServer()->getUrl());
        $this->multi->add($request);
        $this->multi->send();

        $this->assertEquals('idle', $this->multi->getState());

        $this->assertTrue($this->mock->has(CurlMulti::ADD_REQUEST));
        $this->assertTrue($this->mock->has(CurlMulti::COMPLETE));

        $this->assertEquals('Body', $request->getResponse()->getBody()->__toString());

        // Sending it again will not do anything because there are no requests
        $this->multi->send();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::send
     */
    public function testSendsRequestsThroughCurl()
    {
        $this->getServer()->enqueue(array(
            "HTTP/1.1 204 No content\r\n" .
            "Content-Length: 0\r\n" .
            "Server: Jetty(6.1.3)\r\n\r\n",

            "HTTP/1.1 200 OK\r\n" .
            "Content-Type: text/html; charset=utf-8\r\n" .
            "Content-Length: 4\r\n" .
            "Server: Jetty(6.1.3)\r\n\r\n" .
            "\r\n" .
            "data"
        ));

        $request1 = new Request('GET', $this->getServer()->getUrl());
        $mock1 = $this->getWildcardObserver($request1);
        $request2 = new Request('GET', $this->getServer()->getUrl());
        $mock2 = $this->getWildcardObserver($request2);

        $this->multi->add($request1);
        $this->multi->add($request2);
        $this->multi->send();

        $response1 = $request1->getResponse();
        $response2 = $request2->getResponse();

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $response1);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $response2);

        $this->assertTrue($response1->getBody(true) == 'data' || $response2->getBody(true) == 'data');
        $this->assertTrue($response1->getBody(true) == '' || $response2->getBody(true) == '');
        $this->assertTrue($response1->getStatusCode() == '204' || $response2->getStatusCode() == '204');
        $this->assertNotEquals((string) $response1, (string) $response2);

        $this->assertTrue($mock1->has('request.before_send'));
        $this->assertTrue($mock2->has('request.before_send'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::send
     */
    public function testSendsThroughCurlAndAggregatesRequestExceptions()
    {
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\n" .
            "Content-Type: text/html; charset=utf-8\r\n" .
            "Content-Length: 4\r\n" .
            "Server: Jetty(6.1.3)\r\n" .
            "\r\n" .
            "data",

            "HTTP/1.1 204 No content\r\n" .
            "Content-Length: 0\r\n" .
            "Server: Jetty(6.1.3)\r\n" .
            "\r\n",

            "HTTP/1.1 404 Not Found\r\n" .
            "Content-Length: 0\r\n" .
            "\r\n"
        ));

        $request1 = new Request('GET', $this->getServer()->getUrl());
        $request2 = new Request('HEAD', $this->getServer()->getUrl());
        $request3 = new Request('GET', $this->getServer()->getUrl());
        $this->multi->add($request1);
        $this->multi->add($request2);
        $this->multi->add($request3);

        try {
            $this->multi->send();
            $this->fail('ExceptionCollection not thrown when aggregating request exceptions');
        } catch (ExceptionCollection $e) {

            $this->assertInstanceOf('ArrayIterator', $e->getIterator());
            $this->assertEquals(1, count($e));
            $exceptions = $e->getIterator();

            $response1 = $request1->getResponse();
            $response2 = $request2->getResponse();
            $response3 = $request3->getResponse();

            $this->assertNotEquals((string) $response1, (string) $response2);
            $this->assertNotEquals((string) $response3, (string) $response1);
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $response1);
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $response2);
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $response3);

            $failed = $exceptions[0]->getResponse();
            $this->assertEquals(404, $failed->getStatusCode());
            $this->assertEquals(1, count($e));

            // Test the IteratorAggregate functionality
            foreach ($e as $excep) {
                $this->assertEquals($failed, $excep->getResponse());
            }
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::send
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::processResponse
     */
    public function testCurlErrorsAreCaught()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");

        try {
            $request = RequestFactory::getInstance()->create('GET', 'http://127.0.0.1:9876/');
            $request->setClient(new Client());
            $request->getCurlOptions()->set(CURLOPT_FRESH_CONNECT, true);
            $request->getCurlOptions()->set(CURLOPT_TIMEOUT, 0);
            $request->getCurlOptions()->set(CURLOPT_CONNECTTIMEOUT, 1);
            $request->send();
            $this->fail('CurlException not thrown');
        } catch (CurlException $e) {
            $m = $e->getMessage();
            $this->assertContains('[curl] 7:', $m);
            $this->assertContains('[url] http://127.0.0.1:9876/', $m);
            $this->assertContains('[debug] ', $m);
            $this->assertContains('[info] array (', $m);
            $this->assertContains('Connection refused', $m);
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     */
    public function testRemovesQueuedRequests()
    {
        $request = RequestFactory::getInstance()->create('GET', 'http://127.0.0.1:9876/');
        $request->setClient(new Client());
        $request->setResponse(new Response(200), true);
        $this->multi->add($request);
        $this->multi->send();
        $this->assertTrue($this->mock->has(CurlMulti::ADD_REQUEST));
        $this->assertTrue($this->mock->has(CurlMulti::POLLING) === false);
        $this->assertTrue($this->mock->has(CurlMulti::COMPLETE) !== false);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     */
    public function testRemovesQueuedRequestsAddedInTransit()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $r = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $r->getEventDispatcher()->addListener('request.receive.status_line', function(Event $event) use ($/* Replaced /* Replaced /* Replaced client */ */ */) {
            // Create a request using a queued response
            $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get()->setResponse(new Response(200), true);
            $request->send();
        });

        $r->send();
        $this->assertEquals(1, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     */
    public function testProperlyBlocksBasedOnRequestsInScope()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\nContent-Length: 5\r\n\r\ntest1",
            "HTTP/1.1 200 OK\r\nContent-Length: 5\r\n\r\ntest2",
            "HTTP/1.1 200 OK\r\nContent-Length: 5\r\n\r\ntest3",
            "HTTP/1.1 200 OK\r\nContent-Length: 5\r\n\r\ntest4",
            "HTTP/1.1 200 OK\r\nContent-Length: 5\r\n\r\ntest5",
            "HTTP/1.1 200 OK\r\nContent-Length: 5\r\n\r\ntest6",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());

        $requests = array(
            $/* Replaced /* Replaced /* Replaced client */ */ */->get(),
            $/* Replaced /* Replaced /* Replaced client */ */ */->get(),
            $/* Replaced /* Replaced /* Replaced client */ */ */->get()
        );

        $callback = function(Event $event) use ($/* Replaced /* Replaced /* Replaced client */ */ */) {
            $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->set('called', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('called') + 1);
            $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
            if ($/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('called') <= 2) {
                $request->getEventDispatcher()->addListener('request.complete', function(Event $event) use ($/* Replaced /* Replaced /* Replaced client */ */ */) {
                    $/* Replaced /* Replaced /* Replaced client */ */ */->head()->send();
                });
            }
            $request->send();
        };

        $requests[0]->getEventDispatcher()->addListener('request.complete', $callback);
        $requests[1]->getEventDispatcher()->addListener('request.complete', $callback);
        $requests[2]->getEventDispatcher()->addListener('request.complete', $callback);

        $/* Replaced /* Replaced /* Replaced client */ */ */->send($requests);

        $this->assertEquals(8, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     * @expectedException RuntimeException
     * @expectedExceptionMessage Testing!
     */
    public function testCatchesExceptionsBeforeSendingCurlMulti()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $multi = new CurlMulti();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setCurlMulti($multi);
        $multi->getEventDispatcher()->addListener(CurlMulti::BEFORE_SEND, function() {
            throw new \RuntimeException('Testing!');
        });
        $/* Replaced /* Replaced /* Replaced client */ */ */->get()->send();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::removeErroredRequest
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\ExceptionCollection
     * @expectedExceptionMessage Thrown before sending!
     */
    public function testCatchesExceptionsBeforeSendingRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->getEventDispatcher()->addListener('request.before_send', function() {
            throw new \RuntimeException('Thrown before sending!');
        });
        $/* Replaced /* Replaced /* Replaced client */ */ */->send(array($request));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::removeErroredRequest
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException
     */
    public function testCatchesExceptionsWhenRemovingQueuedRequests()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $r = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $r->getEventDispatcher()->addListener('request.sent', function() use ($/* Replaced /* Replaced /* Replaced client */ */ */) {
            // Create a request using a queued response
            $/* Replaced /* Replaced /* Replaced client */ */ */->get()->setResponse(new Response(404), true)->send();
        });
        $r->send();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::removeErroredRequest
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException
     */
    public function testCatchesExceptionsWhenRemovingQueuedRequestsBeforeSending()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $r = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $r->getEventDispatcher()->addListener('request.before_send', function() use ($/* Replaced /* Replaced /* Replaced client */ */ */) {
            // Create a request using a queued response
            $/* Replaced /* Replaced /* Replaced client */ */ */->get()->setResponse(new Response(404), true)->send();
        });
        $r->send();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::send
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::removeErroredRequest
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\ExceptionCollection
     * @expectedExceptionMessage test
     */
    public function testCatchesRandomExceptionsThrownDuringPerform()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $multi = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Curl\\CurlMulti', array('perform'));
        $multi->expects($this->once())
              ->method('perform')
              ->will($this->throwException(new \Exception('test')));
        $multi->add($/* Replaced /* Replaced /* Replaced client */ */ */->get());
        $multi->send();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::send
     */
    public function testDoesNotSendRequestsDecliningToBeSent()
    {
        $this->getServer()->flush();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->getEventDispatcher()->addListener('request.before_send', function(Event $event) {
            $event['request']->setResponse(new Response(200));
        });

        $multi = new CurlMulti();
        $multi->add($request);
        $multi->send();
        $this->assertEquals(0, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::reset
     */
    public function testHardResetReopensMultiHandle()
    {
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $message = '';
        $plugin = new LogPlugin(new ClosureLogAdapter(function($msg) use (&$message) {
            $message .= $msg . "\n";
        }), LogPlugin::LOG_VERBOSE);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $multi = new CurlMulti();
        $multi->add($request);
        $multi->send();
        $multi->reset(true);
        $multi->add($request);
        $multi->send();

        $this->assertNotContains('Re-using existing connection', $message);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti::checkCurlResult
     */
    public function testThrowsMeaningfulExceptionsForCurlMultiErrors()
    {
        $multi = new CurlMulti();

        // Set the state of the multi object to sending to trigger the exception
        $reflector = new \ReflectionMethod('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti', 'checkCurlResult');
        $reflector->setAccessible(true);

        // Successful
        $reflector->invoke($multi, 0);

        // Known error
        try {
            $reflector->invoke($multi, CURLM_BAD_HANDLE);
            $this->fail('Expected an exception here');
        } catch (CurlException $e) {
            $this->assertContains('The passed-in handle is not a valid CURLM handle.', $e->getMessage());
            $this->assertContains('CURLM_BAD_HANDLE', $e->getMessage());
            $this->assertContains(strval(CURLM_BAD_HANDLE), $e->getMessage());
        }

        // Unknown error
        try {
            $reflector->invoke($multi, 255);
            $this->fail('Expected an exception here');
        } catch (CurlException $e) {
            $this->assertEquals('Unexpected cURL error: 255', $e->getMessage());
        }
    }
}
