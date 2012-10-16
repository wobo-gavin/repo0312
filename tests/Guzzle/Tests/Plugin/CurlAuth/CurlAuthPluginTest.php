<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\CurlAuth;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\CurlAuth\CurlAuthPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\CurlAuth\CurlAuthPlugin
 */
class CurlAuthPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testAddsBasicAuthentication()
    {
        $plugin = new CurlAuthPlugin('michael', 'test');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $this->assertEquals('michael', $request->getUsername());
        $this->assertEquals('test', $request->getPassword());
    }

    public function testAddsDigestAuthentication()
    {
        $plugin = new CurlAuthPlugin('julian', 'test', CURLAUTH_DIGEST);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $this->assertEquals('julian', $request->getUsername());
        $this->assertEquals('test', $request->getPassword());
        $this->assertEquals('julian:test', $request->getCurlOptions()->get(CURLOPT_USERPWD));
        $this->assertEquals(CURLAUTH_DIGEST, $request->getCurlOptions()->get(CURLOPT_HTTPAUTH));
    }
}
