<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock\MockSubject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\EventManager;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AbstractSubjectTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\AbstractSubject::getEventManager
     */
    public function testGetEventManager()
    {
        $subject = new MockSubject();
        $mediator = $subject->getEventManager();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\EventManager', $mediator);
        $this->assertEquals($mediator, $subject->getEventManager());
    }
}