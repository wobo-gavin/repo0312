<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockSubject;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class MockPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::isTemporary
     */
    public function testCanBeTemporary()
    {
        $plugin = new MockPlugin();
        $this->assertFalse($plugin->isTemporary());
        $plugin = new MockPlugin(true);
        $this->assertTrue($plugin->isTemporary());
    }
    
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::count
     */
    public function testIsCountable()
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(Response::factory("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $this->assertEquals(1, count($plugin));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::clearQueue
     * @depends testIsCountable
     */
    public function testCanClearQueue()
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(Response::factory("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $plugin->clearQueue();
        $this->assertEquals(0, count($plugin));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::getQueue
     */
    public function testCanInspectQueue()
    {
        $plugin = new MockPlugin();
        $this->assertInternalType('array', $plugin->getQueue());
        $plugin->addResponse(Response::factory("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $queue = $plugin->getQueue();
        $this->assertInternalType('array', $queue);
        $this->assertEquals(1, count($queue));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::getMockFile
     */
    public function testRetrievesResponsesFromFiles()
    {
        $response = MockPlugin::getMockFile(__DIR__ . '/../../TestData/mock_response');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::getMockFile
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExcpetionWhenResponseFileIsNotFound()
    {
        MockPlugin::getMockFile('missing/filename');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::addResponse
     * @expectedException InvalidArgumentException
     */
    public function testInvalidResponsesThrowAnException()
    {
        $p = new MockPlugin();
        $p->addResponse($this);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::addResponse
     */
    public function testAddsResponseObjectsToQueue()
    {
        $p = new MockPlugin();
        $response = Response::factory("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $p->addResponse($response);
        $this->assertEquals(array($response), $p->getQueue());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::addResponse
     */
    public function testAddsResponseFilesToQueue()
    {
        $p = new MockPlugin();
        $p->addResponse(__DIR__ . '/../../TestData/mock_response');
        $this->assertEquals(1, count($p));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::update
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::addResponse
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::dequeue
     * @depends testAddsResponseFilesToQueue
     */
    public function testAddsMockResponseToRequestFromClient()
    {
        $p = new MockPlugin();
        $response = MockPlugin::getMockFile(__DIR__ . '/../../TestData/mock_response');
        $p->addResponse($response);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://localhost:123/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach($p, 9999);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        $this->assertSame($response, $request->getResponse());
        $this->assertEquals(0, count($p));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::update
     * @depends testAddsResponseFilesToQueue
     */
    public function testUpdateIgnoresWhenEmpty()
    {
        $p = new MockPlugin();
        $p->update(new MockSubject(), 'request.create');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::update
     * @depends testAddsResponseFilesToQueue
     */
    public function testUpdateIgnoresOtherEvents()
    {
        $p = new MockPlugin();
        $p->addResponse(MockPlugin::getMockFile(__DIR__ . '/../../TestData/mock_response'));
        $p->update(new MockSubject(), 'foobar');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::update
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\MockPlugin::dequeue
     * @depends testAddsMockResponseToRequestFromClient
     */
    public function testDetachesTemporaryWhenEmpty()
    {
        $p = new MockPlugin(true);
        $p->addResponse(MockPlugin::getMockFile(__DIR__ . '/../../TestData/mock_response'));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://localhost:123/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach($p, 9999);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        $this->assertFalse($/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->hasObserver($p));
    }
}