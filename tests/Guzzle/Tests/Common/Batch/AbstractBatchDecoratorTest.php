<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\Batch;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\AbstractBatchDecorator
 */
class AbstractBatchDecoratorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testProxiesToWrappedObject()
    {
        $batch = new Batch(
            $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchTransferInterface'),
            $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchDivisorInterface')
        );

        $decoratorA = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\AbstractBatchDecorator')
            ->setConstructorArgs(array($batch))
            ->getMockForAbstractClass();

        $decoratorB = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\AbstractBatchDecorator')
            ->setConstructorArgs(array($decoratorA))
            ->getMockForAbstractClass();

        $decoratorA->add('foo');
        $this->assertFalse($decoratorB->isEmpty());
        $this->assertFalse($batch->isEmpty());
        $this->assertEquals(array($decoratorB, $decoratorA), $decoratorB->getDecorators());
        $this->assertEquals(array(), $decoratorB->flush());
    }
}
