<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherTrait;

class AbstractHasDispatcher implements HasDispatcherInterface
{
    use HasDispatcherTrait;
}

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherTrait
 */
class HasDispatcherTraitTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testHelperAttachesSubscribers()
    {
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\AbstractHasDispatcher')
            ->getMockForAbstractClass();

        $result = $mock->getEventDispatcher();
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventDispatcherInterface', $result);
        $result2 = $mock->getEventDispatcher();
        $this->assertSame($result, $result2);
    }
}
