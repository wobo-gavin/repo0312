<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\History;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\History
 */
class HistoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddsForErrorEvent()
    {
        $request = new Request('GET', '/');
        $response = new Response(400);
        $t = new Transaction(new Client(), $request);
        $t->response = $response;
        $e = new RequestException('foo', $request, $response);
        $ev = new ErrorEvent($t, $e);
        $h = new History(2);
        $h->onError($ev);
        // Only tracks when no response is present
        $this->assertEquals([], $h->getRequests());
    }

    public function testLogsConnectionErrors()
    {
        $request = new Request('GET', '/');
        $t = new Transaction(new Client(), $request);
        $e = new RequestException('foo', $request);
        $ev = new ErrorEvent($t, $e);
        $h = new History();
        $h->onError($ev);
        $this->assertEquals([$request], $h->getRequests());
    }

    public function testMaintainsLimitValue()
    {
        $request = new Request('GET', '/');
        $response = new Response(200);
        $t = new Transaction(new Client(), $request);
        $t->response = $response;
        $ev = new CompleteEvent($t);
        $h = new History(2);
        $h->onComplete($ev);
        $h->onComplete($ev);
        $h->onComplete($ev);
        $this->assertEquals(2, count($h));
        $this->assertSame($request, $h->getLastRequest());
        $this->assertSame($response, $h->getLastResponse());
        foreach ($h as $trans) {
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface', $trans['request']);
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface', $trans['response']);
        }
        return $h;
    }

    /**
     * @depends testMaintainsLimitValue
     */
    public function testClearsHistory($h)
    {
        $this->assertEquals(2, count($h));
        $h->clear();
        $this->assertEquals(0, count($h));
    }

    public function testWorksWithMock()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://localhost/']);
        $h = new History();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);
        $mock = new Mock([new Response(200), new Response(201), new Response(202)]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $request->setMethod('PUT');
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $request->setMethod('POST');
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertEquals(3, count($h));

        $result = implode("\n", array_map(function ($line) {
            return strpos($line, 'User-Agent') === 0
                ? 'User-Agent:'
                : trim($line);
        }, explode("\n", $h)));

        $this->assertEquals("> GET / HTTP/1.1
Host: localhost
User-Agent:

< HTTP/1.1 200 OK

> PUT / HTTP/1.1
Host: localhost
User-Agent:

< HTTP/1.1 201 Created

> POST / HTTP/1.1
Host: localhost
User-Agent:

< HTTP/1.1 202 Accepted
", $result);
    }

    public function testCanCastToString()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://localhost/']);
        $h = new History();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($h);

        $mock = new Mock(array(
            new Response(301, array('Location' => '/redirect1', 'Content-Length' => 0)),
            new Response(307, array('Location' => '/redirect2', 'Content-Length' => 0)),
            new Response(200, array('Content-Length' => '2'), Stream::factory('HI'))
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertEquals(3, count($h));

        $h = str_replace("\r", '', $h);
        $this->assertContains("> GET / HTTP/1.1\nHost: localhost\nUser-Agent:", $h);
        $this->assertContains("< HTTP/1.1 301 Moved Permanently\nLocation: /redirect1", $h);
        $this->assertContains("< HTTP/1.1 307 Temporary Redirect\nLocation: /redirect2", $h);
        $this->assertContains("< HTTP/1.1 200 OK\nContent-Length: 2\n\nHI", $h);
    }
}
