<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;

class MockPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::getSubscribedEvents
     */
    public function testDescribesSubscribedEvents()
    {
        $this->assertInternalType('array', MockPlugin::getSubscribedEvents());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::getAllEvents
     */
    public function testDescribesEvents()
    {
        $this->assertInternalType('array', MockPlugin::getAllEvents());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::isTemporary
     */
    public function testCanBeTemporary()
    {
        $plugin = new MockPlugin();
        $this->assertFalse($plugin->isTemporary());
        $plugin = new MockPlugin(null, true);
        $this->assertTrue($plugin->isTemporary());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::count
     */
    public function testIsCountable()
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(Response::fromMessage("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $this->assertEquals(1, count($plugin));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::clearQueue
     * @depends testIsCountable
     */
    public function testCanClearQueue()
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(Response::fromMessage("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $plugin->clearQueue();
        $this->assertEquals(0, count($plugin));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::getQueue
     */
    public function testCanInspectQueue()
    {
        $plugin = new MockPlugin();
        $this->assertInternalType('array', $plugin->getQueue());
        $plugin->addResponse(Response::fromMessage("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $queue = $plugin->getQueue();
        $this->assertInternalType('array', $queue);
        $this->assertEquals(1, count($queue));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::getMockFile
     */
    public function testRetrievesResponsesFromFiles()
    {
        $response = MockPlugin::getMockFile(__DIR__ . '/../../TestData/mock_response');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::getMockFile
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionWhenResponseFileIsNotFound()
    {
        MockPlugin::getMockFile('missing/filename');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::addResponse
     * @expectedException InvalidArgumentException
     */
    public function testInvalidResponsesThrowAnException()
    {
        $p = new MockPlugin();
        $p->addResponse($this);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::addResponse
     */
    public function testAddsResponseObjectsToQueue()
    {
        $p = new MockPlugin();
        $response = Response::fromMessage("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $p->addResponse($response);
        $this->assertEquals(array($response), $p->getQueue());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::addResponse
     */
    public function testAddsResponseFilesToQueue()
    {
        $p = new MockPlugin();
        $p->addResponse(__DIR__ . '/../../TestData/mock_response');
        $this->assertEquals(1, count($p));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::onRequestCreate
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::addResponse
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::dequeue
     * @depends testAddsResponseFilesToQueue
     */
    public function testAddsMockResponseToRequestFromClient()
    {
        $p = new MockPlugin();
        $response = MockPlugin::getMockFile(__DIR__ . '/../../TestData/mock_response');
        $p->addResponse($response);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://localhost:123/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($p, 9999);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        $this->assertSame($response, $request->getResponse());
        $this->assertEquals(0, count($p));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::onRequestCreate
     * @depends testAddsResponseFilesToQueue
     */
    public function testUpdateIgnoresWhenEmpty()
    {
        $p = new MockPlugin();
        $p->onRequestCreate(new Event());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::onRequestCreate
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::dequeue
     * @depends testAddsMockResponseToRequestFromClient
     */
    public function testDetachesTemporaryWhenEmpty()
    {
        $p = new MockPlugin(null, true);
        $p->addResponse(MockPlugin::getMockFile(__DIR__ . '/../../TestData/mock_response'));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://localhost:123/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($p, 9999);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        $this->assertFalse($this->hasSubscriber($/* Replaced /* Replaced /* Replaced client */ */ */, $p));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::__construct
     */
    public function testLoadsResponsesFromConstructor()
    {
        $p = new MockPlugin(array(new Response(200)));
        $this->assertEquals(1, $p->count());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::getReceivedRequests
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin::flush
     */
    public function testStoresMockedRequests()
    {
        $p = new MockPlugin(array(new Response(200), new Response(200)));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://localhost:123/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($p, 9999);

        $request1 = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request1->send();
        $this->assertEquals(array($request1), $p->getReceivedRequests());

        $request2 = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request2->send();
        $this->assertEquals(array($request1, $request2), $p->getReceivedRequests());

        $p->flush();
        $this->assertEquals(array(), $p->getReceivedRequests());
    }
}
