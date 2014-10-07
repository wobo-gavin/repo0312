<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\FutureResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;
use React\Promise\Deferred;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock
 */
class MockTest extends \PHPUnit_Framework_TestCase
{
    public static function createFuture(
        callable $wait,
        callable $cancel = null
    ) {
        $deferred = new Deferred();
        return new FutureResponse(
            $deferred->promise(),
            function () use ($deferred, $wait) {
                $deferred->resolve($wait());
            },
            $cancel
        );
    }

    public function testDescribesSubscribedEvents()
    {
        $mock = new Mock();
        $this->assertInternalType('array', $mock->getEvents());
    }

    public function testIsCountable()
    {
        $plugin = new Mock();
        $plugin->addResponse((new MessageFactory())->fromMessage("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $this->assertEquals(1, count($plugin));
    }

    public function testCanClearQueue()
    {
        $plugin = new Mock();
        $plugin->addResponse((new MessageFactory())->fromMessage("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $plugin->clearQueue();
        $this->assertEquals(0, count($plugin));
    }

    public function testRetrievesResponsesFromFiles()
    {
        $tmp = tempnam('/tmp', 'tfile');
        file_put_contents($tmp, "HTTP/1.1 201 OK\r\nContent-Length: 0\r\n\r\n");
        $plugin = new Mock();
        $plugin->addResponse($tmp);
        unlink($tmp);
        $this->assertEquals(1, count($plugin));
        $q = $this->readAttribute($plugin, 'queue');
        $this->assertEquals(201, $q[0]->getStatusCode());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowsExceptionWhenInvalidResponse()
    {
        (new Mock())->addResponse(false);
    }

    public function testAddsMockResponseToRequestFromClient()
    {
        $response = new Response(200);
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $m = new Mock([$response]);
        $ev = new BeforeEvent($t);
        $m->onBefore($ev);
        $this->assertSame($response, $t->response);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testUpdateThrowsExceptionWhenEmpty()
    {
        $p = new Mock();
        $ev = new BeforeEvent(new Transaction(new Client(), new Request('GET', '/')));
        $p->onBefore($ev);
    }

    public function testReadsBodiesFromMockedRequests()
    {
        $m = new Mock([new Response(200)]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($m);
        $body = Stream::factory('foo');
        $/* Replaced /* Replaced /* Replaced client */ */ */->put('/', ['body' => $body]);
        $this->assertEquals(3, $body->tell());
    }

    public function testCanMockBadRequestExceptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/');
        $ex = new RequestException('foo', $request);
        $mock = new Mock([$ex]);
        $this->assertCount(1, $mock);
        $request->getEmitter()->attach($mock);

        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
            $this->fail('Did not dequeue an exception');
        } catch (RequestException $e) {
            $this->assertSame($e, $ex);
            $this->assertSame($request, $ex->getRequest());
        }
    }

    public function testCanMockFutureResponses()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/', ['future' => true]);
        $response = new Response(200);
        $future = self::createFuture(function () use ($response) {
            return $response;
        });
        $mock = new Mock([$future]);
        $this->assertCount(1, $mock);
        $request->getEmitter()->attach($mock);
        $res = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertSame($future, $res);
        $this->assertFalse($this->readAttribute($res, 'isRealized'));
        $this->assertSame($response, $res->wait());
    }

    public function testCanMockExceptionFutureResponses()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/', ['future' => true]);
        $future = self::createFuture(function () use ($request) {
            throw new RequestException('foo', $request);
        });

        $mock = new Mock([$future]);
        $request->getEmitter()->attach($mock);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertSame($future, $response);
        $this->assertFalse($this->readAttribute($response, 'isRealized'));

        try {
            $response->wait();
            $this->fail('Did not throw');
        } catch (RequestException $e) {
            $this->assertContains('foo', $e->getMessage());
        }
    }

    public function testCanMockFailedFutureResponses()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/', ['future' => true]);

        // The first mock will be a mocked future response.
        $future = self::createFuture(function () use ($/* Replaced /* Replaced /* Replaced client */ */ */) {
            // When dereferenced, we will set a mocked response and send
            // another request.
            $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://httpbin.org', ['events' => [
                'before' => function (BeforeEvent $e) {
                    $e->intercept(new Response(404));
                }
            ]]);
        });

        $mock = new Mock([$future]);
        $request->getEmitter()->attach($mock);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertSame($future, $response);
        $this->assertFalse($this->readAttribute($response, 'isRealized'));

        try {
            $response->wait();
            $this->fail('Did not throw');
        } catch (RequestException $e) {
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }
    }
}
