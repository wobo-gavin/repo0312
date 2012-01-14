<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AbstractHasAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher::getAllEvents
     */
    public function testDoesNotRequireRegisteredEvents()
    {
        $this->assertEquals(array(), AbstractHasDispatcher::getAllEvents());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher::setEventDispatcher
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher::getEventDispatcher
     */
    public function testAllowsDispatcherToBeInjected()
    {
        $d = new EventDispatcher();
        $mock = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher');
        $this->assertSame($mock, $mock->setEventDispatcher($d));
        $this->assertSame($d, $mock->getEventDispatcher());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher::getEventDispatcher
     */
    public function testCreatesDefaultEventDispatcherIfNeeded()
    {
        $mock = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher');
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventDispatcher', $mock->getEventDispatcher());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher::dispatch
     */
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
}