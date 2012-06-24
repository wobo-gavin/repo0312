<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\ServiceBuilderPlugin;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ServiceBuilderPluginTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Plugin\ServiceBuilderPlugin
     */
    public function testPluginPassPluginsThroughToClients()
    {
        $s = new ServiceBuilder(array(
            'michael.mock' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
                'params' => array(
                    'base_url' => 'http://www.test.com/',
                    'subdomain' => 'michael',
                    'password' => 'test',
                    'username' => 'michael',
                )
            )
        ));

        $plugin = $this->getMock('Symfony\Component\EventDispatcher\EventSubscriberInterface');

        $plugin::staticExpects($this->any())
             ->method('getSubscribedEvents')
             ->will($this->returnValue(array('/* Replaced /* Replaced /* Replaced client */ */ */.create_request' => 'onRequestCreate')));

        $s->addSubscriber(new ServiceBuilderPlugin(array($plugin)));

        $c = $s->get('michael.mock');
        $this->assertTrue($c->getEventDispatcher()->hasListeners('/* Replaced /* Replaced /* Replaced client */ */ */.create_request'));

        $listeners = $c->getEventDispatcher()->getListeners('/* Replaced /* Replaced /* Replaced client */ */ */.create_request');
        $this->assertSame($plugin, $listeners[0][0]);
        $this->assertEquals('onRequestCreate', $listeners[0][1]);
    }
}
