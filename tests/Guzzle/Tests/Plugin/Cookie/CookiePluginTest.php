<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Cookie;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\Cookie;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookieJar\ArrayCookieJar;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookiePlugin;

/**
 * @group server
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookiePlugin
 */
class CookiePluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testExtractsAndStoresCookies()
    {
        $response = new Response(200);
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookieJar\ArrayCookieJar')
            ->setMethods(array('addCookiesFromResponse'))
            ->getMock();

        $mock->expects($this->exactly(1))
            ->method('addCookiesFromResponse')
            ->with($response);

        $plugin = new CookiePlugin($mock);
        $plugin->onRequestSent(new Event(array(
            'response' => $response
        )));
    }

    public function testAddsCookiesToRequests()
    {
        $cookie = new Cookie(array(
            'name'  => 'foo',
            'value' => 'bar'
        ));

        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cookie\CookieJar\ArrayCookieJar')
            ->setMethods(array('getMatchingCookies'))
            ->getMock();

        $mock->expects($this->once())
            ->method('getMatchingCookies')
            ->will($this->returnValue(array($cookie)));

        $plugin = new CookiePlugin($mock);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.example.com');
        $plugin->onRequestBeforeSend(new Event(array(
            'request' => $request
        )));

        $this->assertEquals('bar', $request->getCookie('foo'));
    }

    public function testCookiesAreExtractedFromRedirectResponses()
    {
        $plugin = new CookiePlugin(new ArrayCookieJar());
        $this->getServer()->enqueue(array(
            "HTTP/1.1 302 Moved Temporarily\r\n" .
            "Set-Cookie: test=583551; expires=Wednesday, 23-Mar-2050 19:49:45 GMT; path=/\r\n" .
            "Location: /redirect\r\n\r\n",

            "HTTP/1.1 200 OK\r\n" .
            "Content-Length: 0\r\n\r\n",

            "HTTP/1.1 200 OK\r\n" .
            "Content-Length: 0\r\n\r\n"
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->send();

        $this->assertEquals('test=583551', $request->getHeader('Cookie'));

        $requests = $this->getServer()->getReceivedRequests(true);

        // Confirm subsequent requests have the cookie.
        $this->assertEquals('test=583551', $requests[2]->getHeader('Cookie'));

        // Confirm the redirected request has the cookie.
        $this->assertEquals('test=583551', $requests[1]->getHeader('Cookie'));
    }

    public function testCookiesAreNotAddedWhenParamIsSet()
    {
        $jar = new ArrayCookieJar();
        $plugin = new CookiePlugin($jar);

        $jar->add(new Cookie(array(
            'domain'  => 'example.com',
            'path'    => '/',
            'name'    => 'test',
            'value'   => 'hi',
            'expires' => time() + 3600
        )));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://example.com');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);

        // Ensure that it is normally added
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->setResponse(new Response(200), true);
        $request->send();
        $this->assertEquals('hi', $request->getCookie('test'));

        // Now ensure that it is not added
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->getParams()->set('cookies.disable', true);
        $request->setResponse(new Response(200), true);
        $request->send();
        $this->assertNull($request->getCookie('test'));
    }

    public function testProvidesCookieJar()
    {
        $jar = new ArrayCookieJar();
        $plugin = new CookiePlugin($jar);
        $this->assertSame($jar, $plugin->getCookieJar());
    }
}
