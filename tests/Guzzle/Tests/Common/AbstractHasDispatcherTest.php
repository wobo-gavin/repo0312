<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher
 */
class AbstractHasAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testDoesNotRequireRegisteredEvents()
    {
        $this->assertEquals(array(), AbstractHasDispatcher::getAllEvents());
    }

    public function testAllowsDispatcherToBeInjected()
    {
        $d = new EventDispatcher();
        $mock = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher');
        $this->assertSame($mock, $mock->setEventDispatcher($d));
        $this->assertSame($d, $mock->getEventDispatcher());
    }

    public function testCreatesDefaultEventDispatcherIfNeeded()
    {
        $mock = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher');
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventDispatcher', $mock->getEventDispatcher());
    }

    public function testHelperDispatchesEvents()
    {
        $data = array();
        $mock = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher');
        $mock->getEventDispatcher()->addListener('test', function(Event $e) use (&$data) {
            $data = $e->getIterator()->getArrayCopy();
        });
        $mock->dispatch('test', array(
            'param' => 'abc'
        ));
        $this->assertEquals(array(
            'param' => 'abc',
        ), $data);
    }

    public function testHelperAttachesSubscribers()
    {
        $mock = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher');
        $subscriber = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventSubscriberInterface');

        $dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcher')
            ->setMethods(array('addSubscriber'))
            ->getMock();

        $dispatcher->expects($this->once())
            ->method('addSubscriber');

        $mock->setEventDispatcher($dispatcher);
        $mock->addSubscriber($subscriber);
    }
}
