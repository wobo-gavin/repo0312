<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

class AbstractEventTest extends \PHPUnit_Framework_TestCase
{
    public function testStopsPropagation()
    {
        $e = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\AbstractEvent')
            ->getMockForAbstractClass();
        $this->assertFalse($e->isPropagationStopped());
        $e->stopPropagation();
        $this->assertTrue($e->isPropagationStopped());
    }
}
