<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;

class EventTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @return Event
     */
    private function getEvent()
    {
        return new Event(array(
            'test'  => '123',
            'other' => '456',
            'event' => 'test.notify'
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event::__construct
     */
    public function testAllowsParameterInjection()
    {
        $event = new Event(array(
            'test' => '123'
        ));
        $this->assertEquals('123', $event['test']);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event::offsetGet
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event::offsetSet
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event::offsetUnset
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event::offsetExists
     */
    public function testImplementsArrayAccess()
    {
        $event = $this->getEvent();
        $this->assertEquals('123', $event['test']);
        $this->assertNull($event['foobar']);

        $this->assertTrue($event->offsetExists('test'));
        $this->assertFalse($event->offsetExists('foobar'));

        unset($event['test']);
        $this->assertFalse($event->offsetExists('test'));

        $event['test'] = 'new';
        $this->assertEquals('new', $event['test']);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event::getIterator
     */
    public function testImplementsIteratorAggregate()
    {
        $event = $this->getEvent();
        $this->assertInstanceOf('ArrayIterator', $event->getIterator());
    }
}