<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Redirect;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\History\HistoryPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\Redirect
 */
class RedirectTest extends \PHPUnit_Framework_TestCase
{
    public function testRedirectsRequests()
    {
        $mock = new MockPlugin();
        $history = new HistoryPlugin();
        $mock->addMultiple([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect2\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($history);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);

        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/foo');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('/redirect2', $response->getEffectiveUrl());

        // Ensure that two requests were sent
        $requests = $history->getRequests();

        $this->assertEquals('/foo', $requests[0]->getPath());
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('/redirect1', $requests[1]->getPath());
        $this->assertEquals('GET', $requests[1]->getMethod());
        $this->assertEquals('/redirect2', $requests[2]->getPath());
        $this->assertEquals('GET', $requests[2]->getMethod());
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\TooManyRedirectsException
     * @expectedExceptionMessage Will not follow more than
     */
    public function testCanLimitNumberOfRedirects()
    {
        $mock = new MockPlugin([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect2\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect3\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect4\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect5\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect6\r\nContent-Length: 0\r\n\r\n"
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.example.com/foo');
    }

    public function testDefaultBehaviorIsToRedirectWithGetForEntityEnclosingRequests()
    {
        $h = new HistoryPlugin();
        $mock = new MockPlugin([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('http://test.com/foo', ['X-Baz' => 'bar'], 'testing');

        $requests = $h->getRequests();
        $this->assertEquals('POST', $requests[0]->getMethod());
        $this->assertEquals('GET', $requests[1]->getMethod());
        $this->assertEquals('bar', (string) $requests[1]->getHeader('X-Baz'));
        $this->assertEquals('GET', $requests[2]->getMethod());
    }

    public function testCanRedirectWithStrictRfcCompliance()
    {
        $h = new HistoryPlugin();
        $mock = new MockPlugin([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('/foo', ['X-Baz' => 'bar'], 'testing', ['allow_redirects' => 'strict']);

        $requests = $h->getRequests();
        $this->assertEquals('POST', $requests[0]->getMethod());
        $this->assertEquals('POST', $requests[1]->getMethod());
        $this->assertEquals('bar', (string) $requests[1]->getHeader('X-Baz'));
        $this->assertEquals('POST', $requests[2]->getMethod());
    }

    public function testRewindsStreamWhenRedirectingIfNeeded()
    {
        $h = new HistoryPlugin();
        $mock = new MockPlugin([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($h);

        $body = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface')
            ->setMethods(['seek', 'read', 'eof', 'tell'])
            ->getMockForAbstractClass();
        $body->expects($this->once())->method('tell')->will($this->returnValue(1));
        $body->expects($this->once())->method('seek')->will($this->returnValue(true));
        $body->expects($this->any())->method('eof')->will($this->returnValue(true));
        $body->expects($this->any())->method('read')->will($this->returnValue('foo'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('/foo', [], $body, ['allow_redirects' => 'strict']);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\CouldNotRewindStreamException
     * @expectedExceptionMessage Unable to rewind the non-seekable entity body of the request after redirecting
     */
    public function testThrowsExceptionWhenStreamCannotBeRewound()
    {
        $h = new HistoryPlugin();
        $mock = new MockPlugin([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($h);

        $body = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface')
            ->setMethods(['seek', 'read', 'eof', 'tell'])
            ->getMockForAbstractClass();
        $body->expects($this->once())->method('tell')->will($this->returnValue(1));
        $body->expects($this->once())->method('seek')->will($this->returnValue(false));
        $body->expects($this->any())->method('eof')->will($this->returnValue(true));
        $body->expects($this->any())->method('read')->will($this->returnValue('foo'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('/foo', [], $body, ['allow_redirects' => 'strict']);
    }

    public function testRedirectsCanBeDisabledPerRequest()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber(new MockPlugin([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]));
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->put('/', [], 'test', ['allow_redirects' => false]);
        $this->assertEquals(301, $response->getStatusCode());
    }

    public function testCanRedirectWithNoLeadingSlashAndQuery()
    {
        $h = new HistoryPlugin();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber(new MockPlugin([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: redirect?foo=bar\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]));
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('?foo=bar');
        $requests = $h->getRequests();
        $this->assertEquals('http://www.foo.com?foo=bar', $requests[0]->getUrl());
        $this->assertEquals('http://www.foo.com/redirect?foo=bar', $requests[1]->getUrl());
    }

    public function testHandlesRedirectsWithSpacesProperly()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber(new MockPlugin([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect 1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ]));
        $h = new HistoryPlugin();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/foo');
        $reqs = $h->getRequests();
        $this->assertEquals('/redirect%201', $reqs[1]->getResource());
    }
}
