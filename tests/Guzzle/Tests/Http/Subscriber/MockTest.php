<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\Mock;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\Mock
 */
class MockTest extends \PHPUnit_Framework_TestCase
{
    public function testDescribesSubscribedEvents()
    {
        $this->assertInternalType('array', Mock::getSubscribedEvents());
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
        $m->onRequestBeforeSend($ev);
        $this->assertSame($response, $t->getResponse());
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testUpdateThrowsExceptionWhenEmpty()
    {
        $p = new Mock();
        $ev = new BeforeEvent(new Transaction(new Client(), new Request('GET', '/')));
        $p->onRequestBeforeSend($ev);
    }

    public function testReadsBodiesFromMockedRequests()
    {
        $m = new Mock([new Response(200)]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->addSubscriber($m);
        $body = Stream::factory('foo');
        $/* Replaced /* Replaced /* Replaced client */ */ */->put('/', ['body' => $body]);
        $this->assertEquals(3, $body->tell());
    }

    public function testCanMockBadRequestExceptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/');
        $ex = new RequestException('foo', $request);
        $mock = new Mock([$ex]);
        $this->assertCount(1, $mock);
        $request->getEmitter()->addSubscriber($mock);

        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
            $this->fail('Did not dequeue an exception');
        } catch (RequestException $e) {
            $this->assertSame($e, $ex);
            $this->assertSame($request, $ex->getRequest());
        }
    }
}
