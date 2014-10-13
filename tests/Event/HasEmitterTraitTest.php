<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;

class AbstractHasEmitter implements HasEmitterInterface
{
    use HasEmitterTrait;
}

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait
 */
class HasEmitterTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testHelperAttachesSubscribers()
    {
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event\AbstractHasEmitter')
            ->getMockForAbstractClass();

        $result = $mock->getEmitter();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\EmitterInterface', $result);
        $result2 = $mock->getEmitter();
        $this->assertSame($result, $result2);
    }
}
