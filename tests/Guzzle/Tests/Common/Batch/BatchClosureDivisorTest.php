<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchClosureDivisor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchClosureDivisor
 */
class BatchClosureDivisorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testEnsuresCallableIsCallable()
    {
        $d = new BatchClosureDivisor(new \stdClass());
    }

    public function testDividesBatch()
    {
        $queue = new \SplQueue();
        $queue[] = 'foo';
        $queue[] = 'baz';

        $d = new BatchClosureDivisor(function (\SplQueue $queue, $context) {
            return array(
                array('foo'),
                array('baz')
            );
        }, 'Bar!');

        $batches = $d->createBatches($queue);
        $this->assertEquals(array(array('foo'), array('baz')), $batches);
    }
}
