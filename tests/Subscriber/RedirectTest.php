<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Plugin\Redirect;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\History;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Redirect
 */
class RedirectTest extends \PHPUnit_Framework_TestCase
{
    public function testRedirectsRequests()
    {
        $mock = new Mock();
        $history = new History();
        $mock->addMultiple([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect2\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($history);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/foo');
        // Ensure "end" is called only once
        $called = 0;
        $request->getEmitter()->on('end', function () use (&$called) {
            $called++;
        });
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('/redirect2', $response->getEffectiveUrl());

        // Ensure that two requests were sent
        $requests = $history->getRequests(true);

        $this->assertEquals('/foo', $requests[0]->getPath());
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('/redirect1', $requests[1]->getPath());
        $this->assertEquals('GET', $requests[1]->getMethod());
        $this->assertEquals('/redirect2', $requests[2]->getPath());
        $this->assertEquals('GET', $requests[2]->getMethod());

        $this->assertEquals(1, $called);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\TooManyRedirectsException
     * @expectedExceptionMessage Will not follow more than
     */
    public function testCanLimitNumberOfRedirects()
    {
        $mock = new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect2\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect3\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect4\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect5\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect6\r\nContent-Length: 0\r\n\r\n"
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.example.com/foo');
    }

    public function testDefaultBehaviorIsToRedirectWithGetForEntityEnclosingRequests()
    {
        $h = new History();
        $mock = new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('http://test.com/foo', [
            'headers' => ['X-Baz' => 'bar'],
            'body' => 'testing'
        ]);

        $requests = $h->getRequests(true);
        $this->assertEquals('POST', $requests[0]->getMethod());
        $this->assertEquals('GET', $requests[1]->getMethod());
        $this->assertEquals('bar', (string) $requests[1]->getHeader('X-Baz'));
        $this->assertEquals('GET', $requests[2]->getMethod());
    }

    public function testCanRedirectWithStrictRfcCompliance()
    {
        $h = new History();
        $mock = new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('/foo', [
            'headers' => ['X-Baz' => 'bar'],
            'body' => 'testing',
            'allow_redirects' => ['max' => 10, 'strict' => true]
        ]);

        $requests = $h->getRequests(true);
        $this->assertEquals('POST', $requests[0]->getMethod());
        $this->assertEquals('POST', $requests[1]->getMethod());
        $this->assertEquals('bar', (string) $requests[1]->getHeader('X-Baz'));
        $this->assertEquals('POST', $requests[2]->getMethod());
    }

    public function testRewindsStreamWhenRedirectingIfNeeded()
    {
        $h = new History();
        $mock = new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);

        $body = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface')
            ->setMethods(['seek', 'read', 'eof', 'tell'])
            ->getMockForAbstractClass();
        $body->expects($this->once())->method('tell')->will($this->returnValue(1));
        $body->expects($this->once())->method('seek')->will($this->returnValue(true));
        $body->expects($this->any())->method('eof')->will($this->returnValue(true));
        $body->expects($this->any())->method('read')->will($this->returnValue('foo'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('/foo', [
            'body' => $body,
            'allow_redirects' => ['max' => 5, 'strict' => true]
        ]);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\CouldNotRewindStreamException
     * @expectedExceptionMessage Unable to rewind the non-seekable request body after redirecting
     */
    public function testThrowsExceptionWhenStreamCannotBeRewound()
    {
        $h = new History();
        $mock = new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);

        $body = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface')
            ->setMethods(['seek', 'read', 'eof', 'tell'])
            ->getMockForAbstractClass();
        $body->expects($this->once())->method('tell')->will($this->returnValue(1));
        $body->expects($this->once())->method('seek')->will($this->returnValue(false));
        $body->expects($this->any())->method('eof')->will($this->returnValue(true));
        $body->expects($this->any())->method('read')->will($this->returnValue('foo'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('http://example.com/foo', [
            'body' => $body,
            'allow_redirects' => ['max' => 10, 'strict' => true]
        ]);
    }

    public function testRedirectsCanBeDisabledPerRequest()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://test.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]));
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->put('/', ['body' => 'test', 'allow_redirects' => false]);
        $this->assertEquals(301, $response->getStatusCode());
    }

    public function testCanRedirectWithNoLeadingSlashAndQuery()
    {
        $h = new History();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect?foo=bar\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]));
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('?foo=bar');
        $requests = $h->getRequests(true);
        $this->assertEquals('http://www.foo.com?foo=bar', $requests[0]->getUrl());
        $this->assertEquals('http://www.foo.com/redirect?foo=bar', $requests[1]->getUrl());
    }

    public function testHandlesRedirectsWithSpacesProperly()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect 1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ]));
        $h = new History();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/foo');
        $reqs = $h->getRequests(true);
        $this->assertEquals('/redirect%201', $reqs[1]->getResource());
    }

    public function testAddsRefererWhenPossible()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /bar\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ]));
        $h = new History();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/foo', ['allow_redirects' => ['max' => 5, 'referer' => true]]);
        $reqs = $h->getRequests(true);
        $this->assertEquals('http://www.foo.com/foo', $reqs[1]->getHeader('Referer'));
    }

    public function testDoesNotAddRefererWhenChangingProtocols()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'https://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock([
            "HTTP/1.1 301 Moved Permanently\r\n"
            . "Location: http://www.foo.com/foo\r\n"
            . "Content-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ]));
        $h = new History();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/foo', ['allow_redirects' => ['max' => 5, 'referer' => true]]);
        $reqs = $h->getRequests(true);
        $this->assertFalse($reqs[1]->hasHeader('Referer'));
    }

    public function testRedirectsWithGetOn303()
    {
        $h = new History();
        $mock = new Mock([
            "HTTP/1.1 303 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);
        $/* Replaced /* Replaced /* Replaced client */ */ */->post('http://test.com/foo', ['body' => 'testing']);
        $requests = $h->getRequests(true);
        $this->assertEquals('POST', $requests[0]->getMethod());
        $this->assertEquals('GET', $requests[1]->getMethod());
    }

    public function testRelativeLinkBasedLatestRequest()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: http://www.bar.com\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ]));
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $this->assertEquals(
            'http://www.bar.com/redirect',
            $response->getEffectiveUrl()
        );
    }

    public function testUpperCaseScheme()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach(new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: HTTP://www.bar.com\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ]));
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $this->assertEquals(
            'http://www.bar.com',
            $response->getEffectiveUrl()
        );
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\BadResponseException
     * @expectedExceptionMessage Redirect URL, https://foo.com/redirect2, does not use one of the allowed redirect protocols: http
     */
    public function testThrowsWhenRedirectingToInvalidUrlProtocol()
    {
        $mock = new Mock([
            "HTTP/1.1 301 Moved Permanently\r\nLocation: /redirect1\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 301 Moved Permanently\r\nLocation: https://foo.com/redirect2\r\nContent-Length: 0\r\n\r\n"
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.example.com/foo', [
            'allow_redirects' => [
                'protocols' => ['http']
            ]
        ]);
    }
}
