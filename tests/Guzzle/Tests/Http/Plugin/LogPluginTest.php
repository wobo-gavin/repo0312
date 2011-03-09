<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class LogPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var LogPlugin
     */
    private $plugin;

    /**
     * @var ClosureLogAdapter
     */
    private $logAdapter;

    public function setUp()
    {
        $this->logAdapter = new ClosureLogAdapter(
            function($message, $priority, $extras = null) {
                echo $message . ' - ' . $priority . ' ' . $extras . "\n";
            }
        );

        $this->plugin = new LogPlugin($this->logAdapter);
    }

    /**
     * Parse a log message into parts
     *
     * @param string $message Message to parse
     *
     * @return array
     */
    private function parseMessage($message)
    {
        $p = explode(' - ', $message, 4);
        
        $parts['host'] = trim($p[0]);
        $parts['request'] = str_replace('"', '', $p[1]);
        list($parts['code'], $parts['size']) = explode(' ', $p[2]);
        list($parts['time'], $parts['up'], $parts['down']) = explode(' ', $p[3]);
        $parts['extra'] = isset($p[4]) ? $p[4] : null;

        return $parts;
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin::getLogAdapter
     */
    public function testPluginHasLogAdapter()
    {
        $plugin = new LogPlugin($this->logAdapter);
        $this->assertEquals($this->logAdapter, $plugin->getLogAdapter());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin::update
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin::log
     */
    public function testLogsRequestAndResponseContext()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $request = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request('GET', $this->getServer()->getUrl());

        $plugin = new LogPlugin($this->logAdapter);
        $request->getEventManager()->attach($plugin);

        ob_start();
        $request->send();
        $message = ob_get_clean();
        $parts = $this->parseMessage($message);

        $this->assertEquals('127.0.0.1', $parts['host']);
        $this->assertEquals('GET / HTTP/1.1', $parts['request']);
        $this->assertEquals(200, $parts['code']);
        $this->assertEquals(0, $parts['size']);

        $this->assertContains('127.0.0.1 - "GET / HTTP/1.1" - 200 0 - ', $message);
        $this->assertContains('7 /* Replaced /* Replaced /* Replaced guzzle */ */ */_request', $message);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin::update
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin::log
     */
    public function testLogsRequestAndResponseWireHeaders()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ndata");
        $request = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request('GET', $this->getServer()->getUrl());
        $plugin = new LogPlugin($this->logAdapter, true, LogPlugin::WIRE_HEADERS);
        $request->getEventManager()->attach($plugin);

        ob_start();
        $request->send();
        $message = ob_get_clean();

        // Make sure the context was logged
        $this->assertContains('127.0.0.1 - "GET / HTTP/1.1" - 200 4 - ', $message);
        $this->assertContains('7 /* Replaced /* Replaced /* Replaced guzzle */ */ */_request', $message);

        // Check that the headers were logged
        $this->assertContains("GET / HTTP/1.1\r\n", $message);
        $this->assertContains("User-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(), $message);
        $this->assertContains("Accept: */*\r\n", $message);
        $this->assertContains("Accept-Encoding: deflate, gzip", $message);
        $this->assertContains("Host: 127.0.0.1:", $message);

        // Make sure the response headers are present with a line between the request and response
        $this->assertContains("\n\nHTTP/1.1 200 OK\r\nContent-Length: 4", $message);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin::update
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin::log
     */
    public function testLogsRequestAndResponseWireContentAndHeaders()
    {
        $request = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest('PUT', $this->getServer()->getUrl());
        $request->setBody(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory('send'));
        $plugin = new LogPlugin($this->logAdapter, true, LogPlugin::WIRE_FULL);
        $request->getEventManager()->attach($plugin);

        ob_start();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\ndata");
        $request->send();
        $message = ob_get_clean();

        // Make sure the context was logged
        $this->assertContains('127.0.0.1 - "PUT / HTTP/1.1" - 200 4 - ', $message);

        // Check that the headers were logged
        $this->assertContains("PUT / HTTP/1.1\r\n", $message);
        $this->assertContains("User-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(), $message);
        $this->assertContains("Content-Length: 4", $message);

        // Make sure the response headers are present with a line between the request and response
        $this->assertContains("\n\nHTTP/1.1 200 OK\r\nContent-Length: 4", $message);

        // Request payload
        $this->assertContains("\r\nsend", $message);

        // Response body
        $this->assertContains("data", $message);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin
     */
    public function testLogsRequestAndResponseWireContentAndHeadersNonStreamable()
    {
        $request = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest('PUT', $this->getServer()->getUrl());
        $plugin = new LogPlugin($this->logAdapter, true, LogPlugin::WIRE_FULL);
        $request->getEventManager()->attach($plugin);

        // Send the response from the dummy server as the request body
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\nsend");
        $stream = fopen($this->getServer()->getUrl(), 'r');
        $request->setBody(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory($stream, 4));

        $tmpFile = tempnam('/tmp', 'testLogsRequestAndResponseWireContentAndHeadersNonStreamable');
        $request->setResponseBody(\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody::factory(fopen($tmpFile, 'w')));

        ob_start();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 8\r\n\r\nresponse");
        $request->send();
        $message = ob_get_clean();

        // Make sure the context was logged
        $this->assertContains('127.0.0.1 - "PUT / HTTP/1.1" - 200 8 - ', $message);

        // Check that the headers were logged
        $this->assertContains("PUT / HTTP/1.1\r\n", $message);
        $this->assertContains("User-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(), $message);
        $this->assertContains("Content-Length: 4", $message);
        // Request payload
        $this->assertContains("\r\nsend", $message);

        // Make sure the response headers are present with a line between the request and response
        $this->assertContains("\n\nHTTP/1.1 200 OK\r\nContent-Length: 8", $message);
        // Response body
        $this->assertContains("\r\nresponse", $message);

        unlink($tmpFile);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin
     */
    public function testLogsWhenExceptionsAreThrown()
    {
        $request = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request('GET', $this->getServer()->getUrl());
        $plugin = new LogPlugin($this->logAdapter, true, LogPlugin::WIRE_FULL);
        $request->getEventManager()->attach($plugin);

        $this->getServer()->enqueue("HTTP/1.1 404 Not Found\r\nContent-Length: 0\r\n\r\n");

        ob_start();

        try {
            $request->send();
            $this->fail('Exception for 404 was not thrown');
        } catch (\Exception $e) {}

        $message = ob_get_clean();

        $this->assertContains('127.0.0.1 - "GET / HTTP/1.1" - 404 0 - ', $message);
        $this->assertContains("GET / HTTP/1.1\r\n", $message);
        $this->assertContains("User-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(), $message);
        $this->assertContains("\n\nHTTP/1.1 404 Not Found\r\nContent-Length: 0", $message);

        // make sure the extra data was logged
        $this->assertContains("\nUnsuccessful response | [status code] 404 | [reason phrase] Not Found | [url] http://127.0.0.1:", $message);
    }
}