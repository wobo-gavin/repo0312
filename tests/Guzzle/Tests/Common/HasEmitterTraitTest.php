<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasEmitterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasEmitterTrait;

class AbstractHasEmitter implements HasEmitterInterface
{
    use HasEmitterTrait;
}

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasEmitterTrait
 */
class HasDispatcherTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testHelperAttachesSubscribers()
    {
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\AbstractHasEmitter')
            ->getMockForAbstractClass();

        $result = $mock->getEmitter();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\EmitterInterface', $result);
        $result2 = $mock->getEmitter();
        $this->assertSame($result, $result2);
    }
}
