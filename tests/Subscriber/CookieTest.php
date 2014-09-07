<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Cookie;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Cookie\CookieJar;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\History;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Mock;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Subscriber\Cookie
 */
class CookieTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractsAndStoresCookies()
    {
        $request = new Request('GET', '/');
        $response = new Response(200);
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Cookie\CookieJar')
            ->setMethods(array('extractCookies'))
            ->getMock();

        $mock->expects($this->exactly(1))
            ->method('extractCookies')
            ->with($request, $response);

        $plugin = new Cookie($mock);
        $t = new Transaction(new Client(), $request);
        $t->response = $response;
        $plugin->onComplete(new CompleteEvent($t));
    }

    public function testProvidesCookieJar()
    {
        $jar = new CookieJar();
        $plugin = new Cookie($jar);
        $this->assertSame($jar, $plugin->getCookieJar());
    }

    public function testCookiesAreExtractedFromRedirectResponses()
    {
        $jar = new CookieJar();
        $cookie = new Cookie($jar);
        $history = new History();
        $mock = new Mock([
            "HTTP/1.1 302 Moved Temporarily\r\n" .
            "Set-Cookie: test=583551; Domain=www.foo.com; Expires=Wednesday, 23-Mar-2050 19:49:45 GMT; Path=/\r\n" .
            "Location: /redirect\r\n\r\n",
            "HTTP/1.1 200 OK\r\n" .
            "Content-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\n" .
            "Content-Length: 0\r\n\r\n"
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => 'http://www.foo.com']);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($cookie);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->attach($history);

        $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);

        $this->assertEquals('test=583551', $request->getHeader('Cookie'));
        $requests = $history->getRequests();
        // Confirm subsequent requests have the cookie.
        $this->assertEquals('test=583551', $requests[2]->getHeader('Cookie'));
        // Confirm the redirected request has the cookie.
        $this->assertEquals('test=583551', $requests[1]->getHeader('Cookie'));
    }
}
