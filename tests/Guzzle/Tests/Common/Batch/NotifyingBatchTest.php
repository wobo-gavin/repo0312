<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\NotifyingBatch;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\Batch;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\NotifyingBatch
 */
class NotifyingBatchTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testNotifiesAfterFlush()
    {
        $batch = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\Batch', array('flush'), array(
            $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchTransferInterface'),
            $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchDivisorInterface')
        ));

        $batch->expects($this->once())
            ->method('flush')
            ->will($this->returnValue(array('foo', 'baz')));

        $data = array();
        $decorator = new NotifyingBatch($batch, function ($batch) use (&$data) {
            $data[] = $batch;
        });

        $decorator->add('foo')->add('baz');
        $decorator->flush();
        $this->assertEquals(array(array('foo', 'baz')), $data);
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testEnsuresCallableIsValid()
    {
        $batch = new Batch(
            $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchTransferInterface'),
            $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchDivisorInterface')
        );
        $decorator = new NotifyingBatch($batch, 'foo');
    }
}
