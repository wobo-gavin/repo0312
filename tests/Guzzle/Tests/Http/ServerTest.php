<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\EventManager;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\Observer;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\BadResponseException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Server\Action\ResponseAction;

/**
 * Scripted server test case.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ServerTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * Remove node.js generated keep-alive header
     *
     * @param string $response Response
     *
     * @return string
     */
    protected function removeKeepAlive($response)
    {
        return str_replace("Connection: keep-alive\r\n", '', $response);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::getPort
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::getUrl
     */
    public function testConstructorSetsPort()
    {
        $server = new Server();
        $this->assertNotNull($server->getPort());
        $this->assertEquals('http://127.0.0.1:' . $server->getPort() . '/', $server->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::enqueue
     */
    public function testEnqueuesResponses()
    {
        $server = $this->getServer();
        $this->assertTrue($server->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"));
        $r = new Request('GET', $server->getUrl() . '/* Replaced /* Replaced /* Replaced guzzle */ */ */-server/requests');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server
     */
    public function testServerReceivesRequests()
    {
        $server = $this->getServer();
        $this->assertTrue($server->flush());
        $server->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $factory = new RequestFactory();

        $request = RequestFactory::get($server->getUrl());
        $response = $request->send();
        $this->assertEquals(
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            $this->removeKeepAlive((string) $response)
        );

        $this->assertEquals(1, count($server->getReceivedRequests(false)));
        $requests = $server->getReceivedRequests(true);
        $req = $requests[0];
        $this->assertEquals('GET', $req->getMethod());
        $this->assertEquals($request->getUrl(), $req->getUrl());

        $this->assertTrue($server->flush());
        $this->assertEquals(0, count($server->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::isRunning
     * @depends testServerReceivesRequests
     */
    public function testChecksIfAnotherServerIsAlreadyRunning()
    {
        $server = new Server();
        $this->assertTrue($server->isRunning());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::enqueue
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\HttpException
     * @expectedExceptionMessage Responses must be strings or implement Response
     */
    public function testValidatesEnqueuedResponses()
    {
        $this->getServer()->enqueue(false);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::start
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::isRunning
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::stop
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::getPort
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server::flush
     */
    public function testStartsAndStopsListening()
    {
        $server = new Server(8123);
        $this->assertEquals(8123, $server->getPort());
        $this->assertFalse($server->isRunning());
        $this->assertFalse($server->flush());
        $server->start();
        $this->assertTrue($server->isRunning());
        try {
            RequestFactory::get($server->getUrl())->send();
            $this->fail('Server must return 500 error when no responses are queued');
        } catch (BadResponseException $e) {
            $this->assertEquals(500, $e->getResponse()->getStatusCode());
        }
        $this->assertTrue($server->stop());
        $this->assertFalse($server->isRunning());
        $this->assertFalse($server->stop());
    }
}