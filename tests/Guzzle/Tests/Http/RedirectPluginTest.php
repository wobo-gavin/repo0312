<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Redirect;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\RedirectPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\TooManyRedirectsException;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\RedirectPlugin
 */
class RedirectPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testRedirectsRequests()
    {
        // Flush the server and queue up a redirect followed by a successful response
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect2\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ));

        // Create a /* Replaced /* Replaced /* Replaced client */ */ */ that uses the default redirect behavior
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/foo');
        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());
        $requests = $this->getServer()->getReceivedRequests(true);

        // Ensure that two requests were sent
        $this->assertEquals('/foo', $requests[0]->getResource());
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('/redirect1', $requests[1]->getResource());
        $this->assertEquals('GET', $requests[1]->getMethod());
        $this->assertEquals('/redirect2', $requests[2]->getResource());
        $this->assertEquals('GET', $requests[2]->getMethod());

        // Ensure that the previous response was set correctly
        $this->assertEquals(301, $response->getPreviousResponse()->getStatusCode());
        $this->assertEquals('/redirect2', (string) $response->getPreviousResponse()->getHeader('Location'));

        // Ensure that the redirect count was incremented
        $this->assertEquals(2, $request->getParams()->get(RedirectPlugin::REDIRECT_COUNT));

        $c = 0;
        $r = $response->getPreviousResponse();
        while ($r) {
            if ($c == 0) {
                $this->assertEquals('/redirect2', $r->getLocation());
            } else {
                $this->assertEquals('/redirect1', $r->getLocation());
            }
            $c++;
            $r = $r->getPreviousResponse();
        }


        $this->assertEquals(2, $c);
    }

    public function testCanLimitNumberOfRedirects()
    {
        // Flush the server and queue up a redirect followed by a successful response
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect2\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect3\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect4\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect5\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect6\r\nContent-Length: 0\r\n\r\n"
        ));

        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
            $/* Replaced /* Replaced /* Replaced client */ */ */->get('/foo')->send();
            $this->fail('Did not throw expected exception');
        } catch (TooManyRedirectsException $e) {
            // Ensure that the exception message is correct
            $message = $e->getMessage();
            $parts = explode("\n* Sending redirect request\n", $message);
            $this->assertContains('> GET /foo', $parts[0]);
            $this->assertContains('> GET /redirect1', $parts[1]);
            $this->assertContains('> GET /redirect2', $parts[2]);
            $this->assertContains('> GET /redirect3', $parts[3]);
            $this->assertContains('> GET /redirect4', $parts[4]);
            $this->assertContains('> GET /redirect5', $parts[5]);
        }
    }

    public function testDefaultBehaviorIsToRedirectWithGetForEntityEnclosingRequests()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('/foo', array('X-Baz' => 'bar'), 'testing')->send();

        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('POST', $requests[0]->getMethod());
        $this->assertEquals('GET', $requests[1]->getMethod());
        $this->assertEquals('bar', (string) $requests[1]->getHeader('X-Baz'));
        $this->assertEquals('GET', $requests[2]->getMethod());
    }

    public function testCanRedirectWithStrictRfcCompliance()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->post('/foo', array('X-Baz' => 'bar'), 'testing');
        $request->getParams()->set(RedirectPlugin::STRICT_REDIRECTS, true);
        $request->send();

        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('POST', $requests[0]->getMethod());
        $this->assertEquals('POST', $requests[1]->getMethod());
        $this->assertEquals('bar', (string) $requests[1]->getHeader('X-Baz'));
        $this->assertEquals('POST', $requests[2]->getMethod());
    }

    public function testRewindsStreamWhenRedirectingIfNeeded()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->put();
        $request->configureRedirects(true);
        $body = EntityBody::factory('foo');
        $body->read(1);
        $request->setBody($body);
        $request->send();
        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals('foo', (string) $requests[0]->getBody());
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\CouldNotRewindStreamException
     */
    public function testThrowsExceptionWhenStreamCannotBeRewound()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n"
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->put();
        $request->configureRedirects(true);
        $body = EntityBody::factory(fopen($this->getServer()->getUrl(), 'r'));
        $body->read(1);
        $request->setBody($body)->send();
    }

    public function testRedirectsCanBeDisabledPerRequest()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array("HTTP/1.1 301 Foo\r\nLocation: /foo\r\nContent-Length: 0\r\n\r\n"));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->put();
        $request->configureRedirects(false, 0);
        $this->assertEquals(301, $request->send()->getStatusCode());
    }

    public function testCanRedirectWithNoLeadingSlashAndQuery()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue(array(
            "HTTP/1.1 301 Moved Permanently\r\nLocation: redirect?foo=bar\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('?foo=bar');
        $request->send();
        $requests = $this->getServer()->getReceivedRequests(true);
        $this->assertEquals($this->getServer()->getUrl() . '?foo=bar', $requests[0]->getUrl());
        $this->assertEquals($this->getServer()->getUrl() . 'redirect?foo=bar', $requests[1]->getUrl());
        // Ensure that the history on the actual request is correct
        $this->assertEquals($this->getServer()->getUrl() . '?foo=bar', $request->getUrl());
        $this->assertEquals(
            $this->getServer()->getUrl() . 'redirect?foo=bar',
            $request->getResponse()->getRequest()->getUrl()
        );
    }
}
