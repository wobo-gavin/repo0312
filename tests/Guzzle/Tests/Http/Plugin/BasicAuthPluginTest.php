<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Plugin;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\BasicAuthPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\BasicAuthPlugin
 */
class BasicAuthPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testAddsBasicAuthentication()
    {
        $plugin = new BasicAuthPlugin('michael', 'test');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($plugin);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $this->assertEquals('michael', $request->getUsername());
        $this->assertEquals('test', $request->getPassword());
    }
}